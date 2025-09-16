<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ToolController extends Controller
{   
    public function getToolsChart()
    {
        $tools = DB::table('pj_tools as t')
            ->leftJoin('content_views as c', function ($j) {
                $j->on('c.viewable_id', '=', 't.tool_id')
                ->where('c.viewable_type', \App\Models\Tool::class);
            })
            ->select('t.name', DB::raw('COUNT(c.id) as popularity_score'))
            ->groupBy('t.tool_id', 't.name')
            ->orderByDesc('popularity_score')
            ->get();

        return response()->json($tools);
    }
    public function index(Request $req)
    {
        $q    = $req->input('q');
        $cat  = $req->input('cat');
        $sort = $req->input('sort', 'az');

        $tools = Tool::query()
            ->withCount([
                'views as popularity_score' => function ($q2) {
                    $q2->where('viewable_type', \App\Models\Tool::class);
                }
            ])
            ->when($q, fn($qq)=>$qq->where(function($w) use($q){
                $w->where('name','like',"%$q%")
                ->orWhere('title','like',"%$q%")
                ->orWhere('description','like',"%$q%");
            }))
            ->when($cat, fn($qq)=>$qq->where('category',$cat))
            ->when($sort === 'az',  fn($qq)=>$qq->orderBy('name','asc'))
            ->when($sort === 'za',  fn($qq)=>$qq->orderBy('name','desc'))
            ->when($sort === 'pop', fn($qq)=>$qq->orderBy('popularity_score','desc'))
            ->paginate(9)
            ->withQueryString();

        return view('Tools.Tools', compact('tools'));
    }

    public function show($tool_id)
    {
        $tool = Tool::where('tool_id', $tool_id)->firstOrFail();

        $tool->views()->create([
            'user_id' => Auth::id(),
            'ip'      => request()->ip(),
            'ua'      => substr(request()->userAgent() ?? '', 255),
        ]);

        // ถ้าอยากให้คอลัมน์ใน DB ขยับด้วย (เพื่อ phpMyAdmin)
        $tool->increment('popularity_score');

        return view('Tools.showtool', compact('tool'));
    }



    public function __construct()
    {
        $this->middleware(['auth','can:content.manage'])
            ->only(['create','store','edit','update','destroy']);
    }
}