<?php // app/Models/Category.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $table = 'pj_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $fillable = ['name'];
}
