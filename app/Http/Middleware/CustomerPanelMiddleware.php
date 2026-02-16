<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate;

class CustomerPanelMiddleware extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('filament.customer.auth.login');
    }
}
