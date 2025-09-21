<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // ชื่อตารางให้ตรงกับ DB
    protected $table = 'pj_comments';

    // ถ้าคีย์หลักเป็น comment_id และ auto-increment
    protected $primaryKey = 'comment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = ['post_id','user_id','content','parent_id'];

    // relationships
    public function post()   { return $this->belongsTo(Post::class,  'post_id',  'post_id'); }
    public function user()   { return $this->belongsTo(User::class,  'user_id',  'user_id'); }

    // Reply threading
    public function parent()   { return $this->belongsTo(Comment::class, 'parent_id', 'comment_id'); }
    public function children() { return $this->hasMany(Comment::class,   'parent_id', 'comment_id')->latest(); }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'comment_id')
                    ->oldest()            // เรียงลูก
                    ->with(['user']);     // โหลด user ของลูก
    }

    // Votes on comment
    public function votes()  { return $this->hasMany(VoteComment::class, 'comment_id', 'comment_id'); }

    // Helpers (นับคะแนนจากตารางโหวต)
    public function getUpvotesAttribute()   { return $this->votes->where('value', 1)->count(); }
    public function getDownvotesAttribute() { return $this->votes->where('value', -1)->count(); }
    public function getRouteKeyName()
    {
        return 'comment_id';
    }
}
