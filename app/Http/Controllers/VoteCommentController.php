<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\VoteComment;

class VoteCommentController extends Controller
{
    // POST /comments/{comment}/reply
    public function reply(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'content' => ['required','string','max:1000'],
        ]);

        Comment::create([
            'post_id'   => $comment->post_id,
            'user_id'   => Auth::id(),
            'parent_id' => $comment->comment_id,
            'content'   => $data['content'],
        ]);

        // อัปเดตตัวนับคอมเมนต์ของโพสต์
        $count = Comment::where('post_id', $comment->post_id)->count();
        $comment->post()->update(['comment_count' => $count]);

        return back()->with('status','replied');
    }

    // POST /comments/{comment}/vote
     public function vote(Request $request, Comment $comment)
    {
        $request->validate([
            'value' => ['required', 'in:1,-1']
        ]);

        // 1) เก็บโหวตลง pj_vote_comment
        VoteComment::updateOrCreate(
            ['comment_id' => $comment->comment_id, 'user_id' => Auth::id()],
            ['value'      => (int) $request->value]
        );

        // 2) คำนวณโหวตใหม่ทั้งหมด
        $ups   = VoteComment::where('comment_id', $comment->comment_id)->where('value', 1)->count();
        $downs = VoteComment::where('comment_id', $comment->comment_id)->where('value', -1)->count();
        $score = $ups - $downs;

        // 3) อัปเดตกลับไปยัง pj_comments
        $comment->upvotes   = $ups;
        $comment->downvotes = $downs;
        $comment->score     = $score;
        $comment->save();

        if ($request->ajax()) {
            return response()->json([
                'status'     => 'ok',
                'upvotes'    => $ups,
                'downvotes'  => $downs,
                'score'      => $score,
            ]);
        }

        return back()->with('status', 'comment_voted');
    }
}
