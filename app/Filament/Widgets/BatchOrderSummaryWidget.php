<?php

namespace App\Filament\Widgets;

use App\Models\Batch;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BatchOrderSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Ringkasan Produksi Batch Aktif';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $activeBatch = Batch::where('status', '!=', 'closed')->latest()->first();

        return $table
            ->query(
                Order::query()
                    ->when($activeBatch, fn(Builder $query) => $query->where('batch_id', $activeBatch->id))
                    ->when(!$activeBatch, fn(Builder $query) => $query->whereRaw('1 = 0'))
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info' => 'ready',
                        'success' => 'picked_up',
                    ]),
                Tables\Columns\TextColumn::make('pickup_code')
                    ->label('Kode Pickup')
                    ->copyable(),
                Tables\Columns\TextColumn::make('picked_up_at')
                    ->label('Diambil')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
