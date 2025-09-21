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

    public function getAvatarUrlAttribute($value): string
    {
        // 1) เป็น URL เต็มอยู่แล้ว
        if (!empty($value) && preg_match('~^https?://~i', $value)) {
            return $value;
        }

        if (!empty($value)) {
            // normalize path
            $path = ltrim($value, '/');

            // 2) เก็บแบบ 'storage/avatars/xxx.jpg' -> ใช้ asset ตรงๆ
            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            // 3) เก็บแบบ 'public/avatars/xxx.jpg' (ไฟล์บน disk 'public')
            if (str_starts_with($path, 'public/')) {
                // public/... -> /storage/...
                return asset('storage/' . substr($path, 7));
            }

            // 4) เก็บแบบ 'avatars/xxx.jpg' -> ชี้ผ่าน /storage/avatars/...
            return asset('storage/' . $path);
        }

        // 5) ไม่ได้ตั้งค่า -> fallback ตาม username
        $seed = $this->username ?? 'guest';
        return 'https://i.pravatar.cc/64?u=' . urlencode($seed);
    }
}