<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'pj_roles';
    protected $primaryKey = 'role_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // ถ้าตาราง pj_roles ไม่มี created_at/updated_at ให้เป็น false
    public $timestamps = false; // เปลี่ยนเป็น true ถ้าคุณมี 2 คอลัมน์นี้จริง

    protected $fillable = ['name'];

    // ✅ ชี้ไปที่ SignupUser แทน User
    public function users()
    {
        return $this->hasMany(SignupUser::class, 'role_id', 'role_id');
    }
}
