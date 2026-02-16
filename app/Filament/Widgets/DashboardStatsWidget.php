<?php

namespace App\Filament\Widgets;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $isSuperAdmin = auth()->user()?->hasRole('super_admin');

        $stats = [];

        // Active batch info - visible to all
        $activeBatch = Batch::where('status', 'open')->latest()->first();
        $batchDescription = $activeBatch && $activeBatch->event_date
            ? "📅 " . $activeBatch->event_date->format('d M Y')
            : 'Belum ada batch aktif';
        $stats[] = Stat::make('Batch Aktif', $activeBatch?->name ?? 'Tidak ada')
            ->description($batchDescription)
            ->icon('heroicon-o-cube')
            ->color('primary')
            ->chart([7, 3, 4, 5, 6, 3, 5])
            ->chartColor('primary');

        // Total orders in current batch - visible to all
        $batchOrderCount = $activeBatch ? $activeBatch->orders()->count() : 0;
        $stats[] = Stat::make('Order Batch Ini', $batchOrderCount)
            ->description('Total pesanan di batch aktif')
            ->icon('heroicon-o-shopping-bag')
            ->color('info')
            ->extraAttributes(['class' => 'cursor-pointer'])
            ->chart([3, 5, 7, 4, 6, 8, 5]);

        // Pending pickup count - visible to all
        $pendingPickup = Order::where('status', 'ready')
            ->whereNull('picked_up_at')
            ->count();
        $stats[] = Stat::make('Siap Diambil', $pendingPickup)
            ->description($pendingPickup > 0 ? '🔔 Menunggu pickup' : 'Tidak ada pesanan')
            ->icon('heroicon-o-bell-alert')
            ->color($pendingPickup > 0 ? 'warning' : 'gray');

        // Total customers - visible to all
        $customerCount = Customer::count();
        $stats[] = Stat::make('Total Pelanggan', $customerCount)
            ->description('Pelanggan terdaftar')
            ->icon('heroicon-o-user-group')
            ->color('success')
            ->chart([2, 4, 6, 5, 7, 8, 9]);

        // Financial stats - Superadmin only
        if ($isSuperAdmin) {
            $totalRevenue = Order::whereNotNull('picked_up_at')->sum('total_amount');
            $stats[] = Stat::make('Total Omzet', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Revenue keseluruhan')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->chart([4, 6, 8, 7, 9, 10, 12]);

            $totalProfit = Order::whereNotNull('picked_up_at')->sum('profit');
            $stats[] = Stat::make('Total Profit', 'Rp ' . number_format($totalProfit, 0, ',', '.'))
                ->description('Keuntungan bersih')
                ->icon('heroicon-o-chart-bar')
                ->color('primary')
                ->chart([3, 5, 7, 6, 8, 9, 11]);
        }

        return $stats;
    }
}