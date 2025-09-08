<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentView extends Model
{
    protected $table = 'content_views';
    protected $fillable = ['viewable_type','viewable_id','user_id','ip','ua'];

    public function viewable()
    {
        return $this->morphTo();
    }
}
