<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Active Orders Warning --}}
        @if($this->hasActiveOrders())
            <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-600 dark:bg-amber-900/20">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-amber-500 shrink-0 mt-0.5" />
                    <div>
                        <h3 class="font-semibold text-amber-800 dark:text-amber-200">Profil Tidak Dapat Diubah</h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                            Anda memiliki pesanan yang sedang diproses. Data profil tidak dapat diubah untuk menjaga
                            konsistensi data pesanan.
                            Hubungi admin jika perlu mengubah data.
                        </p>
                        <div class="mt-3 space-y-1">
                            @foreach($this->getActiveOrdersInfo() as $order)
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                                {{ $order['status'] === 'Diproses' ? 'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200' : 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200' }}">
                                        {{ $order['status'] }}
                                    </span>
                                    <span
                                        class="font-mono text-amber-800 dark:text-amber-300">{{ $order['order_number'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Profile Form --}}
        <form wire:submit="updateProfile">
            {{ $this->profileForm }}

            @if(!$this->hasActiveOrders())
                <div class="mt-4 flex justify-end">
                    <x-filament::button type="submit" icon="heroicon-o-check">
                        Simpan Profil
                    </x-filament::button>
                </div>
            @endif
        </form>

        {{-- Password Form --}}
        <form wire:submit="updatePassword">
            {{ $this->passwordForm }}

            <div class="mt-4 flex justify-end">
                <x-filament::button type="submit" color="gray" icon="heroicon-o-lock-closed">
                    Ubah Password
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>