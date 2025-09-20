<?php // app/Models/Tag.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    protected $table = 'pj_tags';
    protected $primaryKey = 'tag_id';
    public $timestamps = false;
    protected $fillable = ['name'];
}
