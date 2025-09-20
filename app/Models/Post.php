<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'pj_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = true;

    protected $fillable = ['user_id','category_id','title','content','image_url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id')->latest();
    }

    public function votes()
    {
        return $this->hasMany(VotePost::class, 'post_id', 'post_id');
    }

    // many-to-many ผ่าน pj_post_tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pj_post_tag', 'post_id', 'tag_id');
    }

    // helpers
    public function getUpvotesAttribute()   { return $this->votes->where('value', 1)->count(); }
    public function getDownvotesAttribute() { return $this->votes->where('value', -1)->count(); }
}


