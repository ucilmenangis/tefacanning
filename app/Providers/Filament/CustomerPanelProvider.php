<?php

namespace App\Providers\Filament;

use App\Filament\Customer\Pages\Dashboard;
use App\Http\Middleware\CustomerPanelMiddleware;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            // ->login() // TEMPORARILY DISABLED FOR AGENT ACCESS
            // ->registration(\App\Filament\Customer\Pages\Auth\Register::class) // TEMPORARILY DISABLED
            // ->passwordReset() // TEMPORARILY DISABLED
            ->brandName('TEFA Canning SIP')
            ->brandLogo(fn() => view('filament.brand-logo'))
            ->darkModeBrandLogo(fn() => view('filament.brand-logo-dark'))
            ->brandLogoHeight('3rem')
            ->sidebarCollapsibleOnDesktop()
            ->favicon(asset('images/politeknik_logo_red.png'))
            ->colors([
                'primary' => [
                    50 => '#fef2f2',
                    100 => '#fee2e2',
                    200 => '#fecaca',
                    300 => '#fca5a5',
                    400 => '#f87171',
                    500 => '#ef4444',
                    600 => '#dc2626',
                    700 => '#b91c1c',
                    800 => '#991b1b',
                    900 => '#7f1d1d',
                    950 => '#450a0a',
                ],
                'danger' => Color::Rose,
                'warning' => Color::Amber,
                'success' => Color::Emerald,
                'info' => Color::Sky,
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            // ->userMenuItems([ // TEMPORARILY DISABLED FOR AGENT ACCESS
            //     MenuItem::make()
            //         ->label('Edit Profil')
            //         ->url(fn(): string => \App\Filament\Customer\Pages\EditProfile::getUrl())
            //         ->icon('heroicon-o-user-circle'),
            // ])
            ->authGuard('customer')
            ->discoverPages(in: app_path('Filament/Customer/Pages'), for: 'App\\Filament\\Customer\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\\Filament\\Customer\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\AutoLoginCustomer::class, // TEMPORARY: Auto-login for agent access
            ])
            // ->authMiddleware([ // TEMPORARILY DISABLED FOR AGENT ACCESS
            //     CustomerPanelMiddleware::class,
            // ])
            ->spa();
    }
}
