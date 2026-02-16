<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-calendar-days" heading="Batch Produksi Terbaru">
        @php $batch = $this->getBatchData(); @endphp

        @if($batch)
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-base font-bold text-gray-900 dark:text-white">{{ $batch['name'] }}</span>
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <x-heroicon-o-check-circle class="w-3 h-3" />
                        Buka
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">Acara
                        </p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-0.5">{{ $batch['event_name'] }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold">
                            Tanggal</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-0.5">{{ $batch['event_date'] }}</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800">
                    <x-heroicon-o-shopping-bag class="w-4 h-4 text-red-600 dark:text-red-400" />
                    <span class="text-sm text-red-700 dark:text-red-300">
                        <strong>{{ $batch['order_count'] }}</strong> pesanan masuk
                    </span>
                </div>

                <a href="/customer/pre-order"
                    class="block w-full text-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">
                    Buat Pre-Order →
                </a>
            </div>
        @else
            <div class="text-center py-6">
                <x-heroicon-o-calendar class="w-8 h-8 mx-auto text-gray-400 mb-2" />
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada batch produksi yang dibuka.</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Silakan tunggu informasi batch berikutnya.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>