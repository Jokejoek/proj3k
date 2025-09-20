<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\VotePost;
use App\Models\Comment;

class EditProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // karma = ผลรวมค่าโหวต (+1/-1) ของ “ทุกโพสต์” ที่ user เป็นเจ้าของ
        // และไม่นับโหวตที่เจ้าของโหวตให้ตัวเอง
        $karma = VotePost::whereHas('post', function ($q) use ($user) {
                        $q->where('user_id', $user->user_id);
                   })
                   ->where('user_id', '!=', $user->user_id)
                   ->sum('value');

        // contribution_score = จำนวนคอมเมนต์ที่ user ไปคอมเมนต์ที่ไหนก็ได้
        $contrib = Comment::where('user_id', $user->user_id)->count();

        // อัปเดตคอลัมน์ในตาราง users ด้วย (มีคอลัมน์อยู่แล้วตามรูป)
        $user->karma = $karma;
        $user->contribution_score = $contrib;
        $user->save();

        return view('editprofile', [
            'user'  => $user,
            'karma' => $karma,
            'contribution' => $contrib,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'avatar'   => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = asset('storage/'.$path);
        }

        $user->username = $data['username'];
        $user->save();

        return back()->with('ok', 'Profile updated');
    }
}

