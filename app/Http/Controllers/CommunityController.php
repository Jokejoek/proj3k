<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\VotePost;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'votes'])
            ->latest()
            ->paginate(10);

        return view('community', compact('posts'));
    }

    public function storeComment(Request $request, Post $post)
{
    $data = $request->validate([
        'content' => ['required', 'string', 'max:1000'],
    ]);

    \App\Models\Comment::create([
        'post_id' => $post->post_id,
        'user_id' => auth()->id(),
        'content' => $data['content'],
    ]);

    // อัปเดต contribution_score = จำนวนคอมเมนต์ทั้งหมดของ user
    $me = auth()->user();
    $me->contribution_score = \App\Models\Comment::where('user_id', $me->user_id)->count();
    $me->save();

    return back()->with('status', 'commented');
}

public function vote(Request $request, Post $post)
{
    $request->validate(['value' => ['required', 'in:1,-1']]);

    // โหวตหรือแก้ไขโหวตของผู้ใช้ปัจจุบัน
    \App\Models\VotePost::updateOrCreate(
        ['post_id' => $post->post_id, 'user_id' => auth()->id()],
        ['value'   => (int) $request->value]
    );

    // อัปเดต karma ของเจ้าของโพสต์ (ไม่นับโหวตตนเอง)
    $owner = $post->user;
    $ownerKarma = \App\Models\VotePost::whereHas('post', function ($q) use ($owner) {
                        $q->where('user_id', $owner->user_id);
                    })
                    ->where('user_id', '!=', $owner->user_id)
                    ->sum('value');

    $owner->karma = $ownerKarma;
    $owner->save();

    // ป้องกันเลื่อนขึ้นบนสุด
    if ($request->ajax()) {
        return response()->json(['status' => 'ok']);
    }
    return back()->with('status', 'voted');
}}