<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-cube" heading="Produk Tersedia">
        @php $products = $this->getProducts(); @endphp

        @if($products->isEmpty())
            <div class="text-center py-6">
                <x-heroicon-o-cube class="w-8 h-8 mx-auto text-gray-400 mb-2" />
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada produk tersedia.</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($products as $product)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <x-heroicon-o-cube class="w-4 h-4 text-red-600 dark:text-red-400" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">{{ $product->sku }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <p class="text-sm font-bold text-red-600 dark:text-red-400">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">per {{ $product->unit }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
