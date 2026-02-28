<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Info --}}
        <div
            class="p-6 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/20 border border-red-200 dark:border-red-800">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-600 flex items-center justify-center">
                    <x-heroicon-o-shopping-cart class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-300">Pre-Order Sarden Kaleng</h3>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                        Selamat datang, <strong>{{ auth('customer')->user()->name ?? 'Guest' }}</strong>! Silakan pilih
                        batch
                        produksi dan produk yang tersedia untuk melakukan pre-order.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-3">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Min. 100 kaleng
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Max. 3000 kaleng
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-200 dark:bg-emerald-800 text-emerald-800 dark:text-emerald-200">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Ambil di Kampus
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="submit">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" size="lg" icon="heroicon-o-paper-airplane">
                    Kirim Pre-Order
                </x-filament::button>
            </div>
        </form>
    </div>

    {{-- Order History --}}
    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <x-heroicon-o-clock class="w-5 h-5 text-red-600" />
            Riwayat Pesanan Anda
        </h3>

        @php
            $customer = auth('customer')->user();
            $orders = $customer ? $customer->orders()->with(['batch', 'products'])->latest()->take(5)->get() : collect();
        @endphp

        @if($orders->isEmpty())
            <div
                class="text-center py-6 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center mb-2">
                    <x-heroicon-o-inbox class="w-4 h-4 text-gray-400" />
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pesanan. Silakan buat pre-order pertama Anda!
                </p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($orders as $order)
                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span
                                    class="font-mono font-bold text-sm text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'ready' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'picked_up' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'processing' => 'Diproses',
                                        'ready' => 'Siap Ambil',
                                        'picked_up' => 'Sudah Diambil',
                                    ];
                                @endphp
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? '' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span>Batch: {{ $order->batch->name ?? '-' }}</span>
                            <span>•</span>
                            <span>{{ $order->products->count() }} produk</span>
                            <span>•</span>
                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                            @if($order->status === 'ready')
                                <span>•</span>
                                <span class="font-bold text-green-600 dark:text-green-400">Kode: {{ $order->pickup_code }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>