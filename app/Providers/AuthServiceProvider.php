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

        $this->registerPolicies();

        Gate::before(function ($u) {
            return ($u && (int)($u->role_id ?? 0) === 1) ? true : null;
        });

        Gate::define('is-admin', fn($u) => (int)($u->role_id ?? 0) === 1);
        Gate::define('is-user',  fn($u) => (int)($u->role_id ?? 0) === 2);
    }
}
