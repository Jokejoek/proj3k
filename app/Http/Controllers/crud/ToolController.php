<?php

namespace App\Http\Controllers\crud;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;   // <-- เพิ่ม
use Illuminate\Validation\Rule;

class ToolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin','can:is-admin']);
    }

    /** LIST + FILTER + PAGINATE */
    public function index(Request $r)
    {
        $q    = trim((string)$r->query('q', ''));
        $cat  = trim((string)$r->query('category', ''));
        // ค่าเริ่มต้นให้เป็น 'recent' (หรือจะส่ง ?sort=newest ก็ได้ผลเดียวกัน)
        $sort = (string)$r->query('sort', 'recent');

        $tools = Tool::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                    ->orWhere('title','like',"%{$q}%")
                    ->orWhere('description','like',"%{$q}%");
                });
            })
            ->when($cat !== '', fn($qq)=>$qq->where('category',$cat))
            // จัดการ sort แบบชัดเจน
            ->when(in_array($sort, ['az','za','pop','recent','newest','oldest'], true) === false,
                fn($qq) => $qq->orderByDesc('created_at')->orderBy('name')) // กันค่าประหลาด
            ->when($sort === 'az',      fn($qq)=>$qq->orderBy('name','asc'))
            ->when($sort === 'za',      fn($qq)=>$qq->orderBy('name','desc'))
            ->when($sort === 'pop',     fn($qq)=>$qq->orderBy('popularity_score','desc')->orderBy('name'))
            ->when(in_array($sort, ['recent','newest'], true),
                fn($qq)=>$qq->orderByDesc('created_at')->orderBy('name'))
            ->when($sort === 'oldest',  fn($qq)=>$qq->orderBy('created_at','asc')->orderBy('name'))
            ->paginate(12)
            ->appends($r->query());

        return view('admin.tools.list', compact('tools','q','cat','sort'));
    }

    /** FORM: CREATE */
    public function create()
    {
        return view('admin.tools.create');
    }

    /** STORE NEW */
    public function store(Request $r)
    {
        $data = $r->validate([
            'name'          => ['required','string','max:100'],
            'category'      => ['required','string','max:50'],
            'title'         => ['nullable','string'],
            'description'   => ['nullable','string'],
            'image_file'    => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
            'download_link' => ['nullable','url','max:255'],
        ]);

        // ถ้ามีไฟล์ → อัปโหลดไป public/tools แล้วเก็บเป็น '/storage/...'
        if ($r->hasFile('image_file')) {
            $path = $r->file('image_file')->store('tools', 'public'); // tools/xxxx.png
            $data['image_url'] = '/storage/'.$path;
        }

        $data['created_by_user_id'] = auth('admin')->id();

        Tool::create($data);

        return redirect()
            ->route('admin.backend.tools.index')
            ->with('status','เพิ่ม Tool สำเร็จ');
    }

    /** FORM: EDIT */
    public function edit(Tool $tool)
    {
        return view('admin.tools.edit', compact('tool'));
    }

    /** UPDATE */
    public function update(Request $r, Tool $tool)
    {
        $data = $r->validate([
            'name'          => ['required','string','max:100'],
            'category'      => ['required','string','max:50'],
            'title'         => ['nullable','string'],
            'description'   => ['nullable','string'],
            'image_file'    => ['nullable','file','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
            'download_link' => ['nullable','url','max:255'],
        ]);

        if ($r->hasFile('image_file')) {
            // ลบรูปเก่า ถ้าเป็นไฟล์ใน storage (เริ่มด้วย /storage/)
            if ($tool->image_url && str_starts_with($tool->image_url, '/storage/')) {
                $old = str_replace('/storage/', '', $tool->image_url); // tools/xxxx.png
                Storage::disk('public')->delete($old);
            }
            $path = $r->file('image_file')->store('tools', 'public');
            $data['image_url'] = '/storage/'.$path;
        }

        $tool->update($data);

        return redirect()
            ->route('admin.backend.tools.index')
            ->with('status','อัปเดต Tool สำเร็จ');
    }

    /** DESTROY */
    public function destroy(Tool $tool)
    {
        // ลบไฟล์รูปด้วยหากมีและอยู่ใน storage
        if ($tool->image_url && str_starts_with($tool->image_url, '/storage/')) {
            $old = str_replace('/storage/', '', $tool->image_url);
            Storage::disk('public')->delete($old);
        }

        $tool->delete();

        return redirect()
            ->route('admin.backend.tools.index')
            ->with('status','ลบ Tool สำเร็จ');
    }
}
