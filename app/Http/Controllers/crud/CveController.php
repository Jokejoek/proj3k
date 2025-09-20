<?php

namespace App\Http\Controllers\crud;

use App\Http\Controllers\Controller;
use App\Models\Cve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class CveController extends Controller
{
    public function __construct()
    {
        // ปรับสิทธิ์ตามที่คุณใช้ในโปรเจกต์ (ตัวอย่าง: แอดมินเท่านั้น)
        $this->middleware(['auth:admin','can:is-admin']);
    }
    /**
     * List + Filter + Paginate CVEs (Back Office)
     * View: resources/views/admin/cve/list.blade.php
     */
    public function index(Request $r)
    {
        Paginator::useBootstrap();

        // รับตัวกรองจาก query string
        $kw          = trim((string) $r->query('q', ''));
        $severity    = array_filter((array) $r->query('severity', [])); // ['Low','High',...]
        $year        = $r->query('year');                                // 2024
        $vendor      = trim((string) $r->query('vendor', ''));
        $product     = trim((string) $r->query('product', ''));
        $exploitable = (bool) $r->query('exploitable', false);
        $sort = $r->query('sort', 'recent');

        $q = Cve::query();

        // ค้นหาแบบกว้าง
        if ($kw !== '') {
            $q->where(function ($w) use ($kw) {
                $w->where('cve_id', 'like', "%{$kw}%")
                  ->orWhere('title', 'like', "%{$kw}%")
                  ->orWhere('vendor', 'like', "%{$kw}%")
                  ->orWhere('product', 'like', "%{$kw}%");
            });
        }

        // กรองตามฟิลด์
        if (!empty($severity))   { $q->whereIn('severity', $severity); }
        if (!empty($year))       { $q->where('year', (int) $year); }
        if ($vendor !== '')      { $q->where('vendor', 'like', "%{$vendor}%"); }
        if ($product !== '')     { $q->where('product', 'like', "%{$product}%"); }
        if ($exploitable)        { $q->where('exploit_available', 1); }

        // เรียงลำดับ
        switch ($sort) {
            case 'cvss_desc':     $q->orderByDesc('cvss_score'); break;
            case 'cvss_asc':      $q->orderBy('cvss_score');     break;
            case 'published_asc': $q->orderBy('published_date'); break;
            case 'year_desc':     $q->orderByDesc('year');       break;
            case 'year_asc':      $q->orderBy('year');           break;

            // ✅ อันนี้ใหม่: เอา “รายการที่เพิ่งสร้าง” ขึ้นก่อน
            case 'recent':
            default:
                $q->orderByDesc('last_modified')   // ใช้คอลัมน์ที่มีอยู่จริง
                ->orderByDesc('published_date');
        }

        $cves  = $q->paginate(10)->withQueryString();

        // ❗ FIX: range ต้องใส่ step เป็น -1 เพื่อเรียงย้อนหลัง (ไม่งั้นจะได้ array ว่าง)
        $years = range((int) date('Y'), 1995, -1);

        return view('admin.cve.list', [
            'cves'    => $cves,
            'years'   => $years,
            'filters' => [
                'q' => $kw,
                'severity' => $severity,
                'year' => $year,
                'vendor' => $vendor,
                'product' => $product,
                'exploitable' => $exploitable ? '1' : '',
                'sort' => $sort,
            ],
        ]);
    }

    /** แสดงฟอร์มสร้าง CVE */
    public function create()
    {
        $severities = ['Low','Medium','High','Critical'];
        // ❗ FIX: ใส่ step -1
        $years = range((int) date('Y'), 1995, -1);
        return view('admin.cve.create', compact('severities','years'));
    }

    /** บันทึกข้อมูลใหม่ */
    public function store(Request $r)
    {
        $validated = $r->validate([
            'cve_id'            => ['required','regex:/^CVE-\d{4}-\d{4,}$/','max:20','unique:pj_vulnerabilities,cve_id'],
            'title'             => ['required','string','max:150'],
            'description'       => ['nullable','string'],
            'severity'          => ['required', Rule::in(['Low','Medium','High','Critical'])],
            'cvss_score'        => ['nullable','numeric','min:0','max:10'],
            'vendor'            => ['nullable','string','max:100'],
            'product'           => ['nullable','string','max:100'],

            // อัปโหลดไฟล์รูปเก็บในระบบ
            'image_file'        => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB

            'year'              => ['nullable','integer','min:1995','max:'.date('Y')],
            'exploit_available' => ['nullable','boolean'],
            'published_date'    => ['nullable','date'],
        ], [
            'cve_id.regex' => 'รูปแบบต้องเป็น CVE-YYYY-NNNN (เช่น CVE-2025-12345)',
        ]);

        // sanitize เบา ๆ
        $data = $validated;
        $data['cve_id']  = strtoupper(trim($data['cve_id']));
        $data['title']   = trim($data['title']);
        if (isset($data['vendor']))  $data['vendor']  = trim($data['vendor']);
        if (isset($data['product'])) $data['product'] = trim($data['product']);

        $data['exploit_available'] = $r->boolean('exploit_available');
        $data['published_date']    = $r->filled('published_date')
            ? Carbon::parse($r->input('published_date'))->startOfDay()
            : now();
        $data['last_modified']     = now();

        // ถ้ามีไฟล์รูป ให้บันทึกลง storage แล้วแปลงเป็น URL (/storage/...)
        if ($r->hasFile('image_file')) {
            $path = $r->file('image_file')->store('cve_images', 'public'); // storage/app/public/cve_images/...
            $data['image_url'] = '/storage/'.$path;
        }

        // ถ้ามีคอลัมน์ created_by_user_id
        if (Schema::hasColumn('pj_vulnerabilities', 'created_by_user_id')) {
            $data['created_by_user_id'] = auth('admin')->id();
        }

        Cve::create($data);

        Alert::success('สร้างรายการสำเร็จ', $data['cve_id']);
        return redirect()->route('admin.backend.cve.index');
    }
    public function edit(Cve $cve)
    {
        $severities = ['Low','Medium','High','Critical'];
        $years = range((int) date('Y'), 1995, -1);

        return view('admin.cve.edit', [
            'cve' => $cve,
            'severities' => $severities,
            'years' => $years,
        ]);
    }

    /** อัปเดตข้อมูล CVE */
    public function update(Request $r, Cve $cve)
    {
        $validated = $r->validate([
            // unique: อนุญาตให้เป็นค่าเดิมของตัวเองได้ (ignore ปัจจุบัน)
            'cve_id'            => [
                'required','regex:/^CVE-\d{4}-\d{4,}$/','max:20',
                Rule::unique('pj_vulnerabilities','cve_id')->ignore($cve->cve_id, 'cve_id'),
            ],
            'title'             => ['required','string','max:150'],
            'description'       => ['nullable','string'],
            'severity'          => ['required', Rule::in(['Low','Medium','High','Critical'])],
            'cvss_score'        => ['nullable','numeric','min:0','max:10'],
            'vendor'            => ['nullable','string','max:100'],
            'product'           => ['nullable','string','max:100'],

            'image_file'        => ['nullable','file','mimes:jpg,jpeg,png,gif,webp','max:5120'], // 5MB

            'year'              => ['nullable','integer','min:1995','max:'.date('Y')],
            'exploit_available' => ['nullable','boolean'],
            'published_date'    => ['nullable','date'],
        ], [
            'cve_id.regex' => 'รูปแบบต้องเป็น CVE-YYYY-NNNN (เช่น CVE-2025-12345)',
        ]);

        // sanitize & normalize
        $data = $validated;
        $data['cve_id'] = strtoupper(trim($data['cve_id']));
        $data['title']  = trim($data['title']);
        if (isset($data['vendor']))  $data['vendor']  = trim($data['vendor']);
        if (isset($data['product'])) $data['product'] = trim($data['product']);

        $data['exploit_available'] = $r->boolean('exploit_available');
        if ($r->filled('published_date')) {
            $data['published_date'] = Carbon::parse($r->input('published_date'))->startOfDay();
        }
        $data['last_modified'] = now();

        // อัปโหลดรูปใหม่ (ลบรูปเก่าถ้าเป็นไฟล์ใน storage/public)
        if ($r->hasFile('image_file')) {
            // ลบไฟล์เก่าถ้าเป็น path ใน storage
            if ($cve->image_url && str_starts_with($cve->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $cve->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            // อัปโหลดไฟล์ใหม่
            $path = $r->file('image_file')->store('cve_images', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        // อัปเดต
        $cve->update($data);

        Alert::success('อัปเดตสำเร็จ', $cve->cve_id);
        return redirect()->route('admin.backend.cve.index');
    }
    /**
     * ลบรายการตาม cve_id (Route Model Binding ใช้ getRouteKeyName() = 'cve_id')
     */
    public function destroy(Cve $cve)
    {
        $id = $cve->cve_id;
        $cve->delete();

        Alert::success("ลบ {$id} สำเร็จ");
        return redirect()->route('admin.backend.cve.index');
    }
}
