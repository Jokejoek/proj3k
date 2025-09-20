<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\VotePost;

class DemoCommunitySeeder extends Seeder
{
    public function run(): void
    {
        // ผู้ใช้ตัวอย่าง
        $pakin = User::updateOrCreate(
            ['email' => 'pakin@example.com'],
            [
                'username'      => 'Pakin',
                'password_hash' => Hash::make('password'),
                'avatar_url'    => null,
                'is_active'     => 1,
            ]
        );

        $araruki = User::updateOrCreate(
            ['email' => 'araruki@example.com'],
            [
                'username'      => 'Araruki007',
                'password_hash' => Hash::make('password'),
                'avatar_url'    => null,
                'is_active'     => 1,
            ]
        );

        $max = User::updateOrCreate(
            ['email' => 'max@example.com'],
            [
                'username'      => 'Max_super',
                'password_hash' => Hash::make('password'),
                'avatar_url'    => null,
                'is_active'     => 1,
            ]
        );

        // โพสต์ตัวอย่าง
        $p1 = Post::create([
            'user_id'   => $pakin->user_id,
            'content'   => 'เพื่อนผมคนนี้หล่อมากเลยครับ เห็นแล้วอยากกระโดดก้นเลย',
            'image_url' => 'https://images.unsplash.com/photo-1544006659-f0b21884ce1d?q=80&w=1200&auto=format',
        ]);

        $p2 = Post::create([
            'user_id'   => $araruki->user_id,
            'content'   => 'ระวังเดี๋ยวบัญชีลับขายยารัวๆแล้วโดนดูดเงินนะ อิอิ',
            'image_url' => null,
        ]);

        $p3 = Post::create([
            'user_id'   => $max->user_id,
            'content'   => 'อยากทราบครับว่าหน่วยจะมีโอกาสต้องเจ็บงานกันอีกรึเปล่า',
            'image_url' => null,
        ]);

        // คอมเมนต์
        Comment::create([
            'post_id' => $p1->post_id,
            'user_id' => $araruki->user_id,
            'content' => 'รูปคมชัดมาก 👍',
        ]);

        Comment::create([
            'post_id' => $p1->post_id,
            'user_id' => $max->user_id,
            'content' => 'ฮามากกกก',
        ]);

        // โหวต
        VotePost::updateOrCreate(
            ['post_id' => $p1->post_id, 'user_id' => $araruki->user_id],
            ['value' => 1]
        );
        VotePost::updateOrCreate(
            ['post_id' => $p1->post_id, 'user_id' => $max->user_id],
            ['value' => 1]
        );
        VotePost::updateOrCreate(
            ['post_id' => $p2->post_id, 'user_id' => $pakin->user_id],
            ['value' => 1]
        );
    }
}
