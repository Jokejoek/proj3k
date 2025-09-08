<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cve;          // โมเดลของคุณ (ชื่อ Cve หรือ CVE ให้ใช้ที่ถูกจริง)
use App\Models\Tool;
use App\Models\ContentView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $since = Carbon::now()->subDays(30);

        $stats = [
            'users'     => User::count(),
            'cves'      => Cve::count(),
            'tools'     => Tool::count(),
            'views_30d' => ContentView::where('created_at','>=',$since)->count(),
        ];

        // Top CVE (30 วัน)
        $topCves = ContentView::select('viewable_id', DB::raw('COUNT(*) as views'))
            ->where('created_at','>=',$since)
            ->where('viewable_type', Cve::class)
            ->groupBy('viewable_id')->orderByDesc('views')->limit(10)->get()
            ->map(function ($row) {
                $cve = Cve::find($row->viewable_id);
                return [
                    'label' => $cve?->cve_id ?? ('CVE#'.$row->viewable_id),
                    'views' => (int)$row->views,
                ];
            });

        // Top Tools (30 วัน)
        $topTools = ContentView::select('viewable_id', DB::raw('COUNT(*) as views'))
            ->where('created_at','>=',$since)
            ->where('viewable_type', Tool::class)
            ->groupBy('viewable_id')->orderByDesc('views')->limit(10)->get()
            ->map(function ($row) {
                $tool = Tool::find($row->viewable_id);
                // เลือกคอลัมน์แสดงชื่อจริงของคุณ เช่น name หรือ title
                $label = $tool?->name ?? $tool?->title ?? ('Tool#'.$row->viewable_id);
                return ['label'=>$label, 'views'=>(int)$row->views];
            });

        $chart = [
            'cve'  => ['labels'=>$topCves->pluck('label'),  'data'=>$topCves->pluck('views')],
            'tool' => ['labels'=>$topTools->pluck('label'), 'data'=>$topTools->pluck('views')],
        ];

        return view('admin.dashboard', compact('stats','chart','since'));

        //dd('dashboard index reached');
    }
}
