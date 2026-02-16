<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $customerId = auth('customer')->id();

        // Single query instead of 4 separate queries
        $stats = Order::where('customer_id', $customerId)
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as total_spent')
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders")
            ->selectRaw("SUM(CASE WHEN status = 'ready' THEN 1 ELSE 0 END) as ready_orders")
            ->first();

        $totalOrders = (int) $stats->total_orders;
        $totalSpent = $stats->total_spent;
        $pendingOrders = (int) $stats->pending_orders;
        $readyOrders = (int) $stats->ready_orders;

        return [
            Stat::make('Total Pesanan', $totalOrders)
                ->description('Pesanan keseluruhan')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary')
                ->chart([2, 4, 3, 5, 4, 6, $totalOrders]),

            Stat::make('Total Belanja', 'Rp ' . number_format((float) $totalSpent, 0, ',', '.'))
                ->description('Akumulasi pengeluaran')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->chart([3, 5, 4, 6, 5, 7, 8]),

            Stat::make('Menunggu Konfirmasi', (string) $pendingOrders)
                ->description($pendingOrders > 0 ? 'Pesanan belum diproses' : 'Semua pesanan diproses')
                ->icon('heroicon-o-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Siap Diambil', (string) $readyOrders)
                ->description($readyOrders > 0 ? '🔔 Segera ambil pesanan Anda!' : 'Belum ada pesanan siap')
                ->icon('heroicon-o-check-circle')
                ->color($readyOrders > 0 ? 'success' : 'gray'),
        ];
    }
}
