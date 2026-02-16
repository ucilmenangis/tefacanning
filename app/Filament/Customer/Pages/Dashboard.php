<?php

namespace App\Filament\Customer\Pages;

use App\Filament\Customer\Widgets\AvailableProductsWidget;
use App\Filament\Customer\Widgets\LatestBatchWidget;
use App\Filament\Customer\Widgets\OrderSummaryWidget;
use App\Filament\Customer\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            OrderSummaryWidget::class,
            LatestBatchWidget::class,
            AvailableProductsWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
