<?php

namespace App\Filament\Widgets;

use App\Models\Batch;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class ProductionSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Ringkasan Produk - Batch Aktif';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $activeBatch = Batch::where('status', '!=', 'closed')->latest()->first();
        $batchId = $activeBatch?->id ?? 0;

        return $table
            ->query(
                \App\Models\Product::query()
                    ->select([
                        'products.id',
                        'products.name',
                        'products.sku',
                        'products.unit',
                        DB::raw('COALESCE(SUM(order_product.quantity), 0) as total_quantity'),
                    ])
                    ->leftJoin('order_product', 'products.id', '=', 'order_product.product_id')
                    ->leftJoin('orders', function ($join) use ($batchId) {
                        $join->on('order_product.order_id', '=', 'orders.id')
                            ->where('orders.batch_id', '=', $batchId)
                            ->whereNull('orders.deleted_at');
                    })
                    ->groupBy('products.id', 'products.name', 'products.sku', 'products.unit')
                    ->having('total_quantity', '>', 0)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Total Kuantitas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan'),
            ])
            ->defaultSort('total_quantity', 'desc')
            ->paginated(false);
    }
}
