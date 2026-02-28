<?php

namespace App\Filament\Customer\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.customer.widgets.welcome-widget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getCustomerData(): array
    {
        $customer = auth('customer')->user();

        // TEMPORARILY: Return placeholder data when no user is logged in (agent access)
        if (!$customer) {
            return [
                'name' => 'Guest',
                'organization' => '-',
                'phone' => '-',
                'email' => '-',
                'member_since' => '-',
            ];
        }

        return [
            'name' => $customer->name,
            'organization' => $customer->organization ?? '-',
            'phone' => $customer->phone ?? '-',
            'email' => $customer->email,
            'member_since' => $customer->created_at->format('d M Y'),
        ];
    }
}
