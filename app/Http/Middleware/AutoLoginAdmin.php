<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * TEMPORARY: Auto-login as the first admin user when no one is authenticated.
 * This allows the agent to browse the admin panel without manual login.
 * 
 * REMOVE THIS MIDDLEWARE WHEN DONE TESTING.
 */
class AutoLoginAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            $user = \App\Models\User::first();
            if ($user) {
                auth()->login($user);
            }
        }

        return $next($request);
    }
}
