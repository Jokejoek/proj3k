<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'pj_tools';
    protected $primaryKey = 'tool_id';   // PK ของคุณ
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;           // มี created_at / updated_at
    

        protected $fillable = [
        'name','category','title','description','image_url',
        'download_link','popularity_score','created_by_user_id',
    ];
    public function views()
    {
        return $this->morphMany(\App\Models\ContentView::class, 'viewable');
    }
    protected static function booted()
    {
        static::deleting(function ($tool) {
            $tool->views()->delete();
        });
    }

}
