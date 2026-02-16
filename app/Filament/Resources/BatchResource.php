<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Manajemen Produksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'open')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Batch')
                            ->description('Detail batch pre-order untuk event kampus')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Batch')
                                    ->placeholder('Contoh: Batch Januari 2026')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'open' => '🟢 Open',
                                        'processing' => '🟡 Processing',
                                        'ready' => '🔵 Ready for Pickup',
                                        'closed' => '🔴 Closed',
                                    ])
                                    ->default('open')
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(1),
                            ])->columns(2),

                        Forms\Components\Section::make('Detail Event')
                            ->description('Informasi event kampus terkait batch ini')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Forms\Components\TextInput::make('event_name')
                                    ->label('Nama Event')
                                    ->placeholder('Contoh: Dies Natalis Polije')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('event_date')
                                    ->label('Tanggal Event')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d F Y'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->placeholder('Catatan tambahan untuk batch ini...')
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
                                    ->content(fn(?Batch $record): string => $record ? $record->orders()->count() . ' pesanan' : '0 pesanan'),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat pada')
                                    ->content(fn(?Batch $record): string => $record?->created_at?->format('d M Y, H:i') ?? '-'),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diupdate')
                                    ->content(fn(?Batch $record): string => $record?->updated_at?->format('d M Y, H:i') ?? '-'),
                            ])
                            ->hidden(fn(?Batch $record) => $record === null),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Batch')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('event_name')
                    ->label('Event')
                    ->searchable()
                    ->icon('heroicon-m-calendar'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'success',
                        'processing' => 'warning',
                        'ready' => 'info',
                        'closed' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'open' => 'Open',
                        'processing' => 'Processing',
                        'ready' => 'Ready',
                        'closed' => 'Closed',
                    }),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pesanan')
                    ->counts('orders')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'processing' => 'Processing',
                        'ready' => 'Ready for Pickup',
                        'closed' => 'Closed',
                    ])
                    ->native(false),
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
            ->emptyStateHeading('Belum ada batch')
            ->emptyStateDescription('Buat batch baru untuk memulai pre-order')
            ->emptyStateIcon('heroicon-o-square-3-stack-3d');
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
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
