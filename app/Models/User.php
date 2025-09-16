<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pj_users';     // << ชื่อตารางจริง
    protected $primaryKey = 'user_id'; // << PK จริง
    public $incrementing = true;
    protected $keyType = 'int';
    // public $timestamps = true; // ถ้ามี created_at/updated_at แล้วไม่ต้องแก้

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        // 'is_active', // ถ้ามีคอลัมน์นี้ในตาราง users ให้เปิดด้วย
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin(): bool
    {
        // ผ่านถ้า role_id = 1 หรือชื่อ role = 'admin'
        return (int)($this->role_id ?? 0) === 1
            || in_array(optional($this->role)->name, ['admin'], true);
    }

    public function scopeUser($q)
    {
        return $q->where('role_id', 2);
    }
}
