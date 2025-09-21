<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = $guards ?: [null];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // แยกปลายทางตาม guard
                return match ($guard) {
                    'admin'    => redirect()->route('admin.dashboard'),
                    'web', null => redirect()->route('home'),
                    default     => redirect()->route('home'),
                };
            }
        }

        return $next($request);
    }
}

