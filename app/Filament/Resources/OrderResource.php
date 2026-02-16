<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Pesanan';

    protected static ?string $pluralLabel = 'Pesanan';

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'ready')->whereNull('picked_up_at')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pesanan')
                            ->description('Detail pesanan pelanggan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->label('Nomor Pesanan')
                                    ->default(fn() => 'ORD-' . strtoupper(Str::random(8)))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-o-hashtag'),
                                Forms\Components\TextInput::make('pickup_code')
                                    ->label('Kode Pickup')
                                    ->default(fn() => strtoupper(Str::random(8)))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-o-key'),
                                Forms\Components\Select::make('customer_id')
                                    ->label('Pelanggan')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->label('No. WhatsApp')
                                            ->tel()
                                            ->helperText('Format: 628xxxxxxxxxx'),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->email(),
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\Select::make('batch_id')
                                    ->label('Batch')
                                    ->relationship('batch', 'name', fn(Builder $query) => $query->where('status', 'open'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false)
                                    ->helperText('Hanya batch dengan status Open yang dapat dipilih'),
                            ])->columns(2),

                        Forms\Components\Section::make('Item Pesanan')
                            ->description('Daftar produk yang dipesan')
                            ->icon('heroicon-o-shopping-cart')
                            ->schema([
                                Forms\Components\Repeater::make('products')
                                    ->label('')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Produk')
                                            ->options(Product::query()->where('is_active', true)->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                if ($state) {
                                                    $product = Product::find($state);
                                                    if ($product) {
                                                        $set('unit_price', $product->price);
                                                        $set('subtotal', $product->price * 1);
                                                    }
                                                }
                                            })
                                            ->columnSpan(4),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Qty')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                                $unitPrice = $get('unit_price') ?? 0;
                                                $set('subtotal', $unitPrice * ($state ?? 1));
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('unit_price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(3),
                                        Forms\Components\TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(3),
                                    ])
                                    ->columns(12)
                                    ->defaultItems(1)
                                    ->addActionLabel('+ Tambah Produk')
                                    ->reorderable(false)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Catatan')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->label('')
                                    ->placeholder('Catatan tambahan untuk pesanan ini...')
                                    ->rows(2),
                            ])
                            ->collapsed(),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status Pesanan')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => '⏳ Pending',
                                        'processing' => '🔄 Processing',
                                        'ready' => '✅ Ready for Pickup',
                                        'picked_up' => '📦 Picked Up',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->native(false),
                            ]),

                        Forms\Components\Section::make('Ringkasan')
                            ->schema([
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total Pesanan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                                Forms\Components\TextInput::make('profit')
                                    ->label('Profit')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->visible(fn() => auth()->user()?->hasRole('super_admin')),
                            ])
                            ->visible(fn() => auth()->user()?->hasRole('super_admin')),

                        Forms\Components\Section::make('Informasi Pickup')
                            ->schema([
                                Forms\Components\Placeholder::make('picked_up_at_display')
                                    ->label('Diambil pada')
                                    ->content(fn(?Order $record): string => $record?->picked_up_at?->format('d M Y, H:i') ?? 'Belum diambil'),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(?Order $record): string => $record?->created_at?->format('d M Y, H:i') ?? '-'),
                            ])
                            ->hidden(fn(?Order $record) => $record === null),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Order $record): ?string => $record->customer?->phone),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Batch')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'ready' => 'success',
                        'picked_up' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'ready' => 'Ready',
                        'picked_up' => 'Picked Up',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'processing' => 'heroicon-o-arrow-path',
                        'ready' => 'heroicon-o-check-circle',
                        'picked_up' => 'heroicon-o-check-badge',
                    }),
                Tables\Columns\TextColumn::make('pickup_code')
                    ->label('Kode Pickup')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode pickup disalin')
                    ->fontFamily('mono')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->visible(fn() => auth()->user()?->hasRole('super_admin'))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('picked_up_at')
                    ->label('Diambil')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'ready' => 'Ready for Pickup',
                        'picked_up' => 'Picked Up',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name')
                    ->native(false),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn(Order $record) => route('order.pdf.download', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pickup')
                    ->label('Pickup')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Pickup')
                    ->modalDescription('Pastikan pelanggan menunjukkan kode pickup yang benar.')
                    ->modalIcon('heroicon-o-check-badge')
                    ->visible(fn(Order $record) => $record->status === 'ready' && !$record->picked_up_at)
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'picked_up',
                            'picked_up_at' => now(),
                        ]);
                        Notification::make()
                            ->success()
                            ->title('Pickup Berhasil')
                            ->body("Pesanan {$record->order_number} telah diambil.")
                            ->send();
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription('Pesanan akan muncul di sini setelah dibuat')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
