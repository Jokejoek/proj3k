<?php

namespace App\Http\Controllers;

use App\Models\CVE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CVEController extends Controller
{
    public function index(Request $req)
    {
        $q    = $req->input('q');
        $sev  = $req->input('sev');
        $year = $req->input('year');
        $sort = $req->input('sort','new');

        $cves = CVE::query()
            ->when($q, fn($qq)=>$qq->where(function($w) use($q){
                $w->where('cve_id','like',"%$q%")
                  ->orWhere('title','like',"%$q%")
                  ->orWhere('vendor','like',"%$q%")
                  ->orWhere('product','like',"%$q%");
            }))
            ->when($sev,  fn($qq)=>$qq->where('severity',$sev))
            ->when($year, fn($qq)=>$qq->where('year',$year))
            ->when($sort==='new', fn($qq)=>$qq->orderBy('published_date','desc'))
            ->when($sort==='old', fn($qq)=>$qq->orderBy('published_date','asc'))
            ->when($sort==='sev', fn($qq)=>$qq->orderBy('cvss_score','desc'))
            ->paginate(6);

        $years = CVE::select('year')->distinct()->orderBy('year','desc')->pluck('year');

        return view('CVE.CVE', compact('cves','years'));
    }

    // ✅ ใช้คลาส CVE (ตรงกับไฟล์ของคุณ) และ view ตามชื่อไฟล์จริง
    public function show(Cve $cve)
    {
        $cve->views()->create([
            'viewable_type' => Cve::class,
            'viewable_id'   => (string) $cve->getKey(),        // cve_id เป็น string
            'user_id'       => Auth::id(),                     // แทน auth()->id() เพื่อตัด warning
            'ip'            => request()->ip(),
            'ua'            => substr((string) request()->userAgent(), 0, 255),
        ]);

        return view('CVE.showCVE', compact('cve'));
    }


    public function __construct()
    {
        $this->middleware(['auth','can:content.manage'])
            ->only(['create','store','edit','update','destroy']);
    }
}