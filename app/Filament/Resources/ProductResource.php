<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Produk';

    protected static ?string $pluralLabel = 'Produk';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'description'];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_active', true)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Produk')
                            ->description('Detail produk TEFA Canning')
                            ->icon('heroicon-o-cube-transparent')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->placeholder('Contoh: Sarden Saus Tomat')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU / Kode Produk')
                                    ->placeholder('TEFA-XXX-001')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->placeholder('Deskripsi produk...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Harga & Stok')
                            ->description('Pengaturan harga dan inventori')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(fn() => !auth()->user()?->hasRole('super_admin'))
                                    ->helperText(fn() => !auth()->user()?->hasRole('super_admin') ? 'Hanya Superadmin yang dapat mengubah harga' : null),
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                Forms\Components\TextInput::make('unit')
                                    ->label('Satuan')
                                    ->placeholder('kaleng, pack, kg')
                                    ->default('kaleng')
                                    ->maxLength(50),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Produk Aktif')
                                    ->default(true)
                                    ->helperText('Produk non-aktif tidak akan ditampilkan saat order'),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Gambar')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Foto Produk')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('products')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(?Product $record): string => $record?->created_at?->format('d M Y, H:i') ?? '-'),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diupdate')
                                    ->content(fn(?Product $record): string => $record?->updated_at?->format('d M Y, H:i') ?? '-'),
                            ])
                            ->hidden(fn(?Product $record) => $record === null),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('/images/product-placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Product $record): string => $record->sku),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->visible(fn() => auth()->user()?->hasRole('super_admin'))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn(Product $record): string => $record->stock > 10 ? 'success' : ($record->stock > 0 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-aktif')
                    ->native(false),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn(Product $record) => $record->isProtected())
                        ->before(function (Product $record) {
                            if ($record->isProtected()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Tidak dapat menghapus produk inti TEFA')
                                    ->danger()
                                    ->send();
                                return false;
                            }
                        }),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada produk')
            ->emptyStateDescription('Tambahkan produk TEFA Canning')
            ->emptyStateIcon('heroicon-o-cube-transparent');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
