<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class SignupUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'pj_users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'karma',
        'contribution_score',
        'avatar_url',
        'last_login_at',
        'is_active',
    ];

    // ซ่อนทั้ง password และ remember_token
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** ความสัมพันธ์กับ role */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /** helper ใช้เช็กสิทธิ์ */
    public function isSuperAdmin(): bool
    {
        return optional($this->role)->name === 'super_admin';
    }
    public function isContentAdmin(): bool
    {
        return optional($this->role)->name === 'content_admin';
    }
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isContentAdmin();
    }

    /** scope ดึงเฉพาะแอดมิน */
    public function scopeAdmins($q)
    {
        return $q->whereHas('role', fn($r) => $r->whereIn('name', ['super_admin', 'content_admin']));
    }

    /** hash รหัสผ่านอัตโนมัติ เมื่อถูก set ผ่าน create/update */
    public function setPasswordAttribute($value)
    {
        // ป้องกัน double-hash
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
}
