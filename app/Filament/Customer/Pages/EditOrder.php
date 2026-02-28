<?php

namespace App\Filament\Customer\Pages;

use App\Models\Batch;
use App\Models\Order;
use App\Models\Product;
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

class EditOrder extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $title = 'Edit Pesanan';

    protected static string $view = 'filament.customer.pages.edit-order';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'edit-order/{order}';

    public ?array $data = [];

    public Order $order;

    public function mount(Order|int $order): void
    {
        $orderId = $order instanceof Order ? $order->id : $order;

        // TEMPORARILY: Abort if no customer is logged in (agent access)
        if (!auth('customer')->id()) {
            abort(403, 'Login required');
        }

        $this->order = Order::where('id', $orderId)
            ->where('customer_id', auth('customer')->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Pre-fill form with existing order data
        $items = $this->order->products->map(fn($product) => [
            'product_id' => $product->id,
            'quantity' => $product->pivot->quantity,
            'unit_price' => $product->pivot->unit_price,
            'subtotal' => $product->pivot->subtotal,
        ])->toArray();

        $this->form->fill([
            'batch_id' => $this->order->batch_id,
            'items' => $items,
            'notes' => $this->order->notes,
        ]);
    }

    public function form(Form $form): Form
    {
        $activeProducts = Product::where('is_active', true)->get();

        return $form
            ->schema([
                Section::make('Informasi Batch')
                    ->description('Batch produksi tidak dapat diubah.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Select::make('batch_id')
                            ->label('Batch Produksi')
                            ->options(
                                Batch::whereIn('id', [$this->order->batch_id])
                                    ->get()
                                    ->mapWithKeys(fn(Batch $batch) => [
                                        $batch->id => $batch->name . ' — ' . ($batch->event_name ?? 'Umum'),
                                    ])->toArray()
                            )
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->native(false)
                            ->helperText('Batch tidak dapat diubah setelah pesanan dibuat.'),
                    ]),

                Section::make('Ubah Produk')
                    ->description('Anda dapat mengubah produk dan jumlah pesanan. Min 100, Max 3000 kaleng per produk.')
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

    public function save(): void
    {
        $data = $this->form->getState();

        // Ensure order is still pending
        $this->order->refresh();
        if ($this->order->status !== 'pending') {
            Notification::make()
                ->title('Pesanan Tidak Dapat Diubah')
                ->body('Pesanan sudah diproses dan tidak dapat diubah lagi.')
                ->danger()
                ->send();
            return;
        }

        // Validate items
        if (empty($data['items'])) {
            Notification::make()
                ->title('Produk Kosong')
                ->body('Silakan tambahkan minimal satu produk.')
                ->danger()
                ->send();
            return;
        }

        // Calculate total from database prices for integrity
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
                ->body('Produk yang dipilih tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // Update order
        $this->order->update([
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? null,
        ]);

        // Sync products - detach all then re-attach
        $this->order->products()->detach();

        foreach ($resolvedItems as $item) {
            $this->order->products()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        Notification::make()
            ->title('Pesanan Berhasil Diperbarui! ✅')
            ->body("Pesanan #{$this->order->order_number} telah diperbarui. Total baru: Rp " . number_format($totalAmount, 0, ',', '.'))
            ->success()
            ->persistent()
            ->send();

        $this->redirect(OrderHistory::getUrl());
    }

    public function getHeaderActions(): array
    {
        return [];
    }
}
