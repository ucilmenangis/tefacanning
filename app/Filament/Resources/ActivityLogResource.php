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

    protected static ?string $navigationGroup = 'Audit & Log';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Log Aktivitas';

    protected static ?string $pluralLabel = 'Log Aktivitas';

    protected static ?string $slug = 'activity-logs';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable()
                    ->description(fn (Activity $record): string => $record->created_at->diffForHumans()),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Aktor')
                    ->default('Sistem')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
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
                        if (!$state) {
                            return '-';
                        }
                        return class_basename($state);
                    })
                    ->badge()
                    ->color('gray')
                    ->description(fn (Activity $record): string => $record->subject_id ? "ID: {$record->subject_id}" : ''),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Aksi')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Target Model')
                    ->options([
                        'App\\Models\\Order' => 'Order',
                        'App\\Models\\Product' => 'Product',
                        'App\\Models\\Customer' => 'Customer',
                        'App\\Models\\Batch' => 'Batch',
                        'App\\Models\\User' => 'User',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateHeading('Belum ada log aktivitas')
            ->emptyStateDescription('Log aktivitas akan muncul ketika ada perubahan data di sistem.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->poll('30s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Aktivitas')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi'),
                        Infolists\Components\TextEntry::make('causer.name')
                            ->label('Aktor')
                            ->default('Sistem')
                            ->icon('heroicon-o-user'),
                        Infolists\Components\TextEntry::make('event')
                            ->label('Aksi')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'info',
                                'deleted' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('subject_type')
                            ->label('Target Model')
                            ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('Target ID'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d M Y, H:i:s'),
                    ])->columns(2),

                Infolists\Components\Section::make('Perubahan Data')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties.old')
                            ->label('Data Lama')
                            ->visible(fn (Activity $record): bool => !empty($record->properties->get('old'))),
                        Infolists\Components\KeyValueEntry::make('properties.attributes')
                            ->label('Data Baru')
                            ->visible(fn (Activity $record): bool => !empty($record->properties->get('attributes'))),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
