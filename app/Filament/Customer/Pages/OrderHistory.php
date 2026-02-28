<?php

namespace App\Filament\Customer\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class OrderHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Riwayat Pesanan';

    protected static ?string $title = 'Riwayat Pesanan';

    protected static string $view = 'filament.customer.pages.order-history';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('customer_id', auth('customer')->id() ?? 0) // fallback to 0 when no user
                    ->with(['batch', 'products'])
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('batch.name')
                    ->label('Batch')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days'),

                TextColumn::make('products_count')
                    ->label('Produk')
                    ->counts('products')
                    ->suffix(' item')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'ready' => 'Siap Ambil',
                        'picked_up' => 'Sudah Diambil',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'ready' => 'success',
                        'picked_up' => 'gray',
                        default => 'secondary',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'processing' => 'heroicon-o-cog-6-tooth',
                        'ready' => 'heroicon-o-check-circle',
                        'picked_up' => 'heroicon-o-check-badge',
                        default => null,
                    }),

                TextColumn::make('pickup_code')
                    ->label('Kode Ambil')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->copyable()
                    ->visible(fn($record) => $record?->status === 'ready'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([5, 10, 25])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn(Order $record) => EditOrder::getUrl(['order' => $record->id]))
                    ->visible(fn(Order $record) => $record->status === 'pending'),

                Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn(Order $record) => url("/order/{$record->id}/pdf"))
                    ->openUrlInNewTab(),

                DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn(Order $record) => $record->status === 'pending')
                    ->modalHeading('Hapus Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->successNotification(
                        Notification::make()
                            ->title('Pesanan berhasil dihapus')
                            ->success()
                    ),
            ])
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription('Silakan buat pre-order terlebih dahulu.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
