<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Audit Log';

    protected static ?string $modelLabel = 'Audit Log';

    protected static ?string $pluralModelLabel = 'Audit Logs';

    protected static ?string $navigationGroup = 'Audit & Log';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('super_admin');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Aktor')
                    ->default('System')
                    ->icon('heroicon-m-user')
                    ->searchable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Target')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) return '-';
                        $map = [
                            'App\\Models\\Order' => 'Pesanan',
                            'App\\Models\\Product' => 'Produk',
                            'App\\Models\\Customer' => 'Pelanggan',
                            'App\\Models\\Batch' => 'Batch',
                            'App\\Models\\User' => 'User',
                        ];
                        return $map[$state] ?? class_basename($state);
                    })
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'App\\Models\\Order' => 'primary',
                        'App\\Models\\Product' => 'success',
                        'App\\Models\\Customer' => 'info',
                        'App\\Models\\Batch' => 'warning',
                        'App\\Models\\User' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID Target')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Aksi')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                    ]),

                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Target')
                    ->options([
                        'App\\Models\\Order' => 'Pesanan',
                        'App\\Models\\Product' => 'Produk',
                        'App\\Models\\Customer' => 'Pelanggan',
                        'App\\Models\\Batch' => 'Batch',
                        'App\\Models\\User' => 'User',
                    ]),

                Tables\Filters\SelectFilter::make('causer_id')
                    ->label('Aktor')
                    ->relationship('causer', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->poll('30s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Log')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Waktu')
                                    ->dateTime('d M Y, H:i:s'),

                                Infolists\Components\TextEntry::make('causer.name')
                                    ->label('Aktor')
                                    ->default('System')
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('event')
                                    ->label('Aksi')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('subject_type')
                                    ->label('Tipe Target')
                                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),

                                Infolists\Components\TextEntry::make('subject_id')
                                    ->label('ID Target'),
                            ]),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi'),
                    ]),

                Infolists\Components\Section::make('Perubahan Data')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties.old')
                            ->label('Data Sebelum')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->properties['old'] ?? null)),

                        Infolists\Components\KeyValueEntry::make('properties.attributes')
                            ->label('Data Sesudah')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->properties['attributes'] ?? null)),
                    ])
                    ->visible(fn ($record) => !empty($record->properties->toArray())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('created_at', '>=', now()->subDay())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
