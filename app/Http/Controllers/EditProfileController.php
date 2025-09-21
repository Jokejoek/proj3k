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
            'username' => ['required','string','max:255'],
            // 'email'    => ['required','email','max:255'], // เอาออก หรือทำเป็น sometimes
            'avatar'   => ['nullable','image','max:2048'],
        ]);

        // ถ้ามีการอัปโหลดรูปใหม่
        if ($request->hasFile('avatar')) {
            // (เลือกทำ) ลบไฟล์เก่าเพื่อลดขยะ
            if ($user->avatar_url && !str_starts_with($user->avatar_url, 'http')) {
                // $user->avatar_url เก็บแบบ "storage/avatars/xxx.jpg"
                $oldPath = str_replace('storage/', '', $user->avatar_url); // => "avatars/xxx.jpg"
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = 'storage/'.$path;  // เก็บเป็น storage/avatars/xxx.jpg
        }

        $user->username = $data['username'];
        // ถ้าต้องการให้แก้ email ได้ ค่อยเพิ่ม input และ validate ทีหลัง
        $user->save();

        return back()->with('success', 'Profile updated!');
    }
}

