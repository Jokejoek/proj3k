<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Model::class => Policy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ผ่านทุก ability ถ้า super admin (role_id = 1)
        Gate::define('access-admin', function ($user) {
            return (int)($user->role_id ?? 0) === 1 ? true : null;
        });

        // ให้ทั้ง super_admin(1) + content_admin(2) เข้าแดชบอร์ด
        Gate::define('admin.area', fn(User $u) => in_array((int)$u->role_id, [1,2], true));

        // จัดการผู้ใช้: เฉพาะ super_admin
        Gate::define('users.manage', fn(User $u) => (int)$u->role_id === 1);

        // เขียนคอนเทนต์: super_admin + content_admin
        Gate::define('content.manage', fn(User $u) => in_array((int)$u->role_id, [1,2], true));
    }
}
