<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotePost extends Model
{
    protected $table = 'pj_vote_post';
    protected $primaryKey = 'vote_id';
    public $timestamps = true;

    protected $fillable = ['post_id','user_id','value']; // 1 | -1

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
