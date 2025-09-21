<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'pj_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = true;

    // อนุญาตให้เขียนคอลัมน์คะแนน/ตัวนับ
    protected $fillable = [
        'user_id','title','content','image_url',
        'upvotes','downvotes','score','comment_count',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'score' => 'integer',
        'comment_count' => 'integer',
    ];

    public function getRouteKeyName(){ return 'post_id'; }

    public function user(){ return $this->belongsTo(User::class, 'user_id', 'user_id'); }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id', 'post_id')
                    ->whereNull('parent_id')
                    ->oldest()
                    ->with(['user','replies.user']);
    }

    public function votes(){ return $this->hasMany(VotePost::class, 'post_id', 'post_id'); }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'pj_post_tag', 'post_id', 'tag_id');
    }

    // ❌ ห้ามมี accessor ชื่อชนกับคอลัมน์
    // public function getUpvotesAttribute(){...}
    // public function getDownvotesAttribute(){...}
}