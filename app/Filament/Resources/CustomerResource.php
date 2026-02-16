<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Pelanggan';

    protected static ?string $pluralLabel = 'Pelanggan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phone', 'email', 'organization'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pelanggan')
                            ->description('Data identitas pelanggan')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Masukkan nama pelanggan')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('organization')
                                    ->label('Organisasi / Instansi')
                                    ->placeholder('Contoh: PT. ABC, Polije, dll')
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Section::make('Kontak')
                            ->description('Informasi kontak untuk notifikasi')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('No. WhatsApp')
                                    ->placeholder('628xxxxxxxxxx')
                                    ->tel()
                                    ->maxLength(20)
                                    ->helperText('Format: 628xxxxxxxxxx (tanpa + atau spasi)'),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->placeholder('email@example.com')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat')
                                    ->placeholder('Alamat lengkap pelanggan')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Statistik')
                            ->schema([
                                Forms\Components\Placeholder::make('orders_count')
                                    ->label('Total Pesanan')
                                    ->content(fn(?Customer $record): string => $record ? $record->orders()->count() . ' pesanan' : '0 pesanan'),
                                Forms\Components\Placeholder::make('total_spent')
                                    ->label('Total Transaksi')
                                    ->content(fn(?Customer $record): string => $record ? 'Rp ' . number_format($record->orders()->sum('total_amount'), 0, ',', '.') : 'Rp 0'),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Terdaftar sejak')
                                    ->content(fn(?Customer $record): string => $record?->created_at?->format('d M Y') ?? '-'),
                            ])
                            ->hidden(fn(?Customer $record) => $record === null),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Customer $record): ?string => $record->organization),
                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Nomor disalin'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pesanan')
                    ->counts('orders')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->striped()
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            ->emptyStateHeading('Belum ada pelanggan')
            ->emptyStateDescription('Pelanggan akan muncul setelah membuat pesanan')
            ->emptyStateIcon('heroicon-o-users');
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
