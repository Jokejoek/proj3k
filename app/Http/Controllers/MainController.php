<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Cve;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        // 1. ดึงโพสต์ล่าสุด 6 รายการ + ผู้ใช้ + คอมเมนต์
       $recentPosts = Post::with(['user:user_id,username,avatar_url'])
            ->select('post_id','user_id','title','created_at','comment_count') // <= เอามาจาก DB
            ->latest()
            ->take(6)
            ->get();

        // 2. ดึง Tools สำหรับ Chart (สมมติคุณเก็บในตาราง tools)
        $toolsChart = DB::table('pj_tools')
            ->select('name', 'popularity_score')
            ->orderByDesc('popularity_score')
            ->get();

        // 3. ดึง CVE ล่าสุด (ใช้ scopeRecent ที่คุณเคยเขียนไว้ใน Model)
        $recentCves = Cve::recent(6)->get();

        // ส่งไปที่ view
        return view('main', [
            'recentPosts' => $recentPosts,
            'toolsChart'  => $toolsChart,
            'recentCves'  => $recentCves,
        ]);
    }
}
