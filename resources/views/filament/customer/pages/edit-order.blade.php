<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Info --}}
        <div class="p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/20 border border-amber-200 dark:border-amber-800">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center">
                    <x-heroicon-o-pencil-square class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-800 dark:text-amber-300">Edit Pesanan</h3>
                    <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">
                        Mengubah pesanan <strong class="font-mono">{{ $this->order->order_number }}</strong>. Anda dapat mengubah produk dan jumlah pesanan, tetapi tidak dapat mengubah batch produksi.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200">
                            <x-heroicon-o-clock class="w-3 h-3" />
                            Status: Menunggu
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <x-heroicon-o-calendar class="w-3 h-3" />
                            Dibuat: {{ $this->order->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ \App\Filament\Customer\Pages\OrderHistory::getUrl() }}"
                   class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali ke Riwayat
                </a>
                <x-filament::button type="submit" size="lg" icon="heroicon-o-check" color="warning">
                    Simpan Perubahan
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
