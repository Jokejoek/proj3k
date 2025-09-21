<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\VotePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * หน้า Community + โหลดโพสต์
     */
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'votes'])
            ->latest()
            ->paginate(10);

        return view('community', compact('posts'));
    }

    /**
     * สร้างโพสต์ใหม่
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required','string','max:150'],
            'content' => ['required','string'],
            'image'   => ['nullable','image','max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            // เก็บไฟล์ไว้ storage/app/public/posts/...
            $path = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id'   => Auth::id(),
            'title'     => $data['title'],
            'content'   => $data['content'],
            'image_url' => $path, // << เก็บเฉพาะ path เช่น "posts/xxx.jpg"
        ]);

        return back()->with('success', 'โพสต์เรียบร้อยแล้ว!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'comments.replies.user']);
        return view('Showcommunity', compact('post'));
    }

    /**
     * บันทึกคอมเมนต์ + อัปเดต contribution_score (ตามจำนวนคอมเมนต์ทั้งหมดของผู้ใช้)
     */
    public function storeComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'content'    => ['required', 'string', 'max:1000'],
            'parent_id'  => ['nullable', 'integer', 'exists:pj_comments,comment_id'],
        ]);

        Comment::create([
            'post_id'   => $post->post_id,
            'user_id'   => Auth::id(),
            'content'   => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        // อัปเดตตัวนับ (ตามที่คุณเลือกว่าจะนับบนสุดหรือทั้งหมด)
        $post->comment_count = Comment::where('post_id', $post->post_id)->count();
        $post->save();

        return back();
    }


    /**
     * โหวตโพสต์ + อัปเดต karma ของเจ้าของโพสต์ (ไม่รวมโหวตตัวเอง)
     */
    public function vote(Request $request, Post $post)
    {
        $data = $request->validate(['value' => 'required|in:1,-1']);
        $userId = auth()->id();

        // upsert โหวตของผู้ใช้
        $existing = $post->votes()->where('user_id', $userId)->first();
        $val = (int)$data['value'];

        if ($existing) {
            if ($existing->value === $val) {
                $existing->delete();                // กดซ้ำ = ยกเลิก
            } else {
                $existing->update(['value' => $val]); // เปลี่ยนค่า
            }
        } else {
            $post->votes()->create(['user_id' => $userId, 'value' => $val]);
        }

        // รวมผลจากตารางโหวต
        $up   = $post->votes()->where('value', 1)->count();
        $down = $post->votes()->where('value', -1)->count();
        $score = $up - $down;

        // เขียนกลับ pj_posts (ใช้ forceFill เพื่อกันปัญหา fillable)
        $post->forceFill([
            'upvotes'   => $up,
            'downvotes' => $down,
            'score'     => $score,
            // 'comment_count' => Comment::where('post_id',$post->post_id)->count(),
        ])->save();

        return back();
    }

}
