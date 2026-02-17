<?php

namespace App\Filament\Customer\Pages;

use App\Models\Batch;
use App\Models\Order;
use App\Models\Product;
use App\Services\FonnteService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class PreOrder extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pre-Order';

    protected static ?string $title = 'Pre-Order Sarden';

    protected static string $view = 'filament.customer.pages.pre-order';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $openBatches = Batch::where('status', 'open')->get();
        $activeProducts = Product::where('is_active', true)->get();

        return $form
            ->schema([
                Section::make('Informasi Batch')
                    ->description('Pilih batch produksi yang tersedia untuk pre-order.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Select::make('batch_id')
                            ->label('Batch Produksi')
                            ->options(
                                $openBatches->mapWithKeys(fn(Batch $batch) => [
                                    $batch->id => $batch->name . ' — ' . ($batch->event_name ?? 'Umum') . ' (' . ($batch->event_date ? $batch->event_date->format('d M Y') : '-') . ')',
                                ])->toArray()
                            )
                            ->required()
                            ->searchable()
                            ->placeholder('Pilih batch produksi...')
                            ->helperText('Pilih batch produksi yang sedang dibuka untuk pre-order.')
                            ->native(false)
                            ->live(),

                        Placeholder::make('batch_info')
                            ->label('')
                            ->content(function (Get $get) {
                                $batchId = $get('batch_id');
                                if (!$batchId) {
                                    return '';
                                }
                                $batch = Batch::find($batchId);
                                if (!$batch) {
                                    return '';
                                }
                                return new \Illuminate\Support\HtmlString(
                                    '<div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">' .
                                    '<div class="flex items-center gap-2 mb-2">' .
                                    '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' .
                                    '<span class="font-semibold text-red-700 dark:text-red-400">Detail Batch</span>' .
                                    '</div>' .
                                    '<p class="text-sm text-gray-700 dark:text-gray-300"><strong>Nama:</strong> ' . e($batch->name) . '</p>' .
                                    '<p class="text-sm text-gray-700 dark:text-gray-300"><strong>Event:</strong> ' . e($batch->event_name ?? '-') . '</p>' .
                                    '<p class="text-sm text-gray-700 dark:text-gray-300"><strong>Tanggal:</strong> ' . ($batch->event_date ? $batch->event_date->format('d M Y') : '-') . '</p>' .
                                    ($batch->description ? '<p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Deskripsi:</strong> ' . e($batch->description) . '</p>' : '') .
                                    '</div>'
                                );
                            })
                            ->visible(fn(Get $get) => filled($get('batch_id'))),
                    ]),

                Section::make('Pilih Produk')
                    ->description('Tambahkan produk yang ingin Anda pesan. Minimal 100 kaleng dan maksimal 3000 kaleng per produk.')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Produk')
                                            ->options(
                                                $activeProducts->mapWithKeys(fn(Product $product) => [
                                                    $product->id => $product->name . ' — Rp ' . number_format((float) $product->price, 0, ',', '.') . '/' . $product->unit,
                                                ])->toArray()
                                            )
                                            ->required()
                                            ->searchable()
                                            ->native(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                                if ($state) {
                                                    $product = Product::find($state);
                                                    if ($product) {
                                                        $set('unit_price', $product->price);
                                                        $qty = (int) $get('quantity');
                                                        $set('subtotal', $qty * $product->price);
                                                    }
                                                }
                                            }),

                                        TextInput::make('quantity')
                                            ->label('Jumlah (Kaleng)')
                                            ->numeric()
                                            ->required()
                                            ->minValue(100)
                                            ->maxValue(3000)
                                            ->default(100)
                                            ->step(50)
                                            ->suffix('kaleng')
                                            ->helperText('Min: 100, Max: 3000')
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                                $productId = $get('product_id');
                                                if ($productId) {
                                                    $product = Product::find($productId);
                                                    if ($product) {
                                                        $set('subtotal', (int) $state * $product->price);
                                                    }
                                                }
                                            }),

                                        TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->dehydrated()
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                TextInput::make('unit_price')
                                    ->hidden()
                                    ->dehydrated(),
                            ])
                            ->columns(1)
                            ->minItems(1)
                            ->maxItems(10)
                            ->defaultItems(1)
                            ->addActionLabel('+ Tambah Produk')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (isset($state['product_id'])) {
                                    $product = Product::find($state['product_id']);
                                    return $product ? $product->name . ' × ' . ($state['quantity'] ?? 0) : null;
                                }
                                return null;
                            }),
                    ]),

                Section::make('Catatan Tambahan')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->placeholder('Tulis catatan atau permintaan khusus untuk pesanan Anda...')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->collapsed(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Validate batch is still open
        $batch = Batch::where('id', $data['batch_id'])->where('status', 'open')->first();
        if (!$batch) {
            Notification::make()
                ->title('Batch Tidak Tersedia')
                ->body('Batch produksi yang dipilih sudah ditutup atau tidak tersedia.')
                ->danger()
                ->send();
            return;
        }

        // Validate items
        if (empty($data['items'])) {
            Notification::make()
                ->title('Produk Kosong')
                ->body('Silakan tambahkan minimal satu produk untuk melakukan pre-order.')
                ->danger()
                ->send();
            return;
        }

        // Calculate total from database prices (not form state) for integrity
        $totalAmount = 0;
        $resolvedItems = [];

        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
            }

            $qty = (int) ($item['quantity'] ?? 0);
            $unitPrice = (float) $product->price;
            $subtotal = $qty * $unitPrice;
            $totalAmount += $subtotal;

            $resolvedItems[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($resolvedItems)) {
            Notification::make()
                ->title('Produk Tidak Valid')
                ->body('Produk yang dipilih tidak ditemukan. Silakan coba lagi.')
                ->danger()
                ->send();
            return;
        }

        // Create order
        $customer = auth('customer')->user();

        $order = Order::create([
            'customer_id' => $customer->id,
            'batch_id' => $data['batch_id'],
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'pickup_code' => strtoupper(Str::random(6)),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'profit' => 0,
            'notes' => $data['notes'] ?? null,
        ]);

        // Attach products with server-verified prices
        foreach ($resolvedItems as $item) {
            $order->products()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Notify superadmins via WhatsApp
        try {
            app(FonnteService::class)->sendNewOrderToSuperAdmin($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Fonnte: Failed to notify superadmin - ' . $e->getMessage());
        }

        // Reset form
        $this->form->fill();

        Notification::make()
            ->title('Pre-Order Berhasil! 🎉')
            ->body("Pesanan #{$order->order_number} berhasil dibuat. Total: Rp " . number_format($totalAmount, 0, ',', '.') . ". Kode pengambilan: {$order->pickup_code}")
            ->success()
            ->persistent()
            ->send();
    }

    public function getHeaderActions(): array
    {
        return [];
    }
}
