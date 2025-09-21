<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteComment extends Model
{
    protected $table = 'pj_vote_comment';      // ปรับให้ตรงกับ DB
    protected $primaryKey = 'vote_id';         // ถ้ามีคอลัมน์นี้ ถ้าไม่มีก็เอาออก
    public $timestamps = true;

     protected $fillable = ['comment_id','user_id','value'];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_id');
    }
    public function votes()
    {
        return $this->hasMany(VoteComment::class, 'comment_id', 'comment_id');
    }
}
