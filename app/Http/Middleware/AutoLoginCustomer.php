<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * TEMPORARY: Auto-login as the first customer when no one is authenticated.
 * This allows the agent to browse the customer panel without manual login.
 * 
 * REMOVE THIS MIDDLEWARE WHEN DONE TESTING.
 */
class AutoLoginCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('customer')->check()) {
            $customer = \App\Models\Customer::first();
            if ($customer) {
                auth('customer')->login($customer);
            }
        }

        return $next($request);
    }
}
