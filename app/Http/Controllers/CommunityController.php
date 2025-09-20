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

    public function store(Request $request)
    {
        $request->validate([
            'content'      => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            // ถ้ายังไม่ใช้หมวด/หัวข้อ ให้ปล่อย nullable ไว้ก่อน
            'category_id'  => ['nullable', 'exists:pj_categories,category_id'],
            'title'        => ['nullable', 'string', 'max:150'],
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        Post::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->input('category_id'), // อาจเป็น null ได้
            'title'       => $request->input('title'),       // อาจเป็น null ได้
            'content'     => $request->content,
            'image_url'   => $imageUrl,
        ]);

        // กลับไปหน้า community พร้อมข้อความสำเร็จ
        return redirect()
            ->route('community.index')
            ->with('status', 'posted');
    }

public function vote(Request $request, Post $post)
{
    $request->validate(['value' => ['required', 'in:1,-1']]);

    VotePost::updateOrCreate(
        ['post_id' => $post->post_id, 'user_id' => auth()->id()],
        ['value'   => (int) $request->value]
    );

    return redirect()
        ->route('community.index')   // กลับไปหน้า community
        ->withFragment('post-'.$post->post_id); // jump ไปยังโพสต์นี้
}

    public function storeComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        Comment::create([
            'post_id' => $post->post_id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return back()->with('status', 'commented');
    }
}
