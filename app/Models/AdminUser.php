<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $table = 'pj_users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    // แอดมิน role_id = 1 (เปลี่ยนได้หากระบบคุณต่างออกไป)
    public const ADMIN_ROLE = 1;

    protected $fillable = ['username', 'email', 'password', 'role_id'];

    protected $hidden = ['password', 'remember_token'];

    // hash อัตโนมัติ เวลา set password
    public function setPasswordAttribute($value)
    {
        if ($value && strlen($value) < 60) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // query เฉพาะผู้ใช้ที่เป็น admin
    public function scopeAdmin($q)
    {
        return $q->where('role_id', self::ADMIN_ROLE);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
