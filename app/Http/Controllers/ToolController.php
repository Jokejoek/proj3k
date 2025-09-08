<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function index(Request $req)
    {
        $q    = $req->input('q');
        $cat  = $req->input('cat');
        $sort = $req->input('sort', 'az');

        $tools = Tool::query()
            ->when($q, fn($qq)=>$qq->where(function($w) use($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('title','like',"%$q%")
                  ->orWhere('description','like',"%$q%");
            }))
            ->when($cat, fn($qq)=>$qq->where('category',$cat))
            ->when($sort === 'az',  fn($qq)=>$qq->orderBy('name','asc'))
            ->when($sort === 'za',  fn($qq)=>$qq->orderBy('name','desc'))
            ->when($sort === 'pop', fn($qq)=>$qq->orderBy('popularity_score','desc'))
            ->paginate(9);

        // NOTE: คุณเก็บไฟล์หน้า list ที่ resources/views/Tools/Tools.blade.php
        return view('Tools.Tools', compact('tools'));
    }

    public function show($tool_id)
    {
        $tool = Tool::where('tool_id', $tool_id)->firstOrFail();

        // บันทึกการกดดู
        $tool->views()->create([
            'viewable_type' => \App\Models\Tool::class,
            'viewable_id'   => (string) $tool->getKey(),   // tool_id เป็น int → cast เป็น string
            'user_id'       => Auth::id(),
            'ip'            => request()->ip(),
            'ua'            => substr(request()->userAgent() ?? '', 0, 255),
        ]);

        return view('Tools.showtool', compact('tool'));
    }



    public function __construct()
    {
        $this->middleware(['auth','can:content.manage'])
            ->only(['create','store','edit','update','destroy']);
    }
}