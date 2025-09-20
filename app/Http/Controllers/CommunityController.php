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

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $imageUrl = asset('storage/'.$path);
        }

        Post::create([
            'user_id'   => Auth::id(),
            'title'     => $data['title'],
            'content'   => $data['content'],
            'image_url' => $imageUrl,
        ]);

        return back()->with('success', 'โพสต์เรียบร้อยแล้ว!');
    }

    /**
     * บันทึกคอมเมนต์ + อัปเดต contribution_score (ตามจำนวนคอมเมนต์ทั้งหมดของผู้ใช้)
     */
    public function storeComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        Comment::create([
            'post_id' => $post->post_id,
            'user_id' => Auth::id(),
            'content' => $data['content'],
        ]);

        // อัปเดต contribution_score = จำนวนคอมเมนต์ทั้งหมดของ user
        $me = Auth::user();
        $me->contribution_score = Comment::where('user_id', $me->user_id)->count();
        $me->save();

        return back()->with('status', 'commented');
    }

    /**
     * โหวตโพสต์ + อัปเดต karma ของเจ้าของโพสต์ (ไม่รวมโหวตตัวเอง)
     */
    public function vote(Request $request, Post $post)
    {
        $request->validate(['value' => ['required', 'in:1,-1']]);

        // โหวตหรือแก้ไขโหวตของผู้ใช้ปัจจุบัน
        VotePost::updateOrCreate(
            ['post_id' => $post->post_id, 'user_id' => Auth::id()],
            ['value'   => (int) $request->value]
        );

        // อัปเดต karma ของเจ้าของโพสต์ (ไม่นับโหวตตนเอง)
        $owner = $post->user;
        $ownerKarma = VotePost::whereHas('post', function ($q) use ($owner) {
                                $q->where('user_id', $owner->user_id);
                           })
                           ->where('user_id', '!=', $owner->user_id)
                           ->sum('value');

        $owner->karma = $ownerKarma;
        $owner->save();

        // ถ้าเรียกแบบ AJAX ก็ส่ง JSON กลับ (กันหน้าเด้งขึ้นบน)
        if ($request->ajax()) {
            return response()->json(['status' => 'ok']);
        }
        return back()->with('status', 'voted');
    }
}
