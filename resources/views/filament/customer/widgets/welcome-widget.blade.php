<x-filament-widgets::widget>
    <x-filament::section>
        @php $data = $this->getCustomerData(); @endphp
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-lg">
                <span class="text-xl font-bold text-white">{{ strtoupper(substr($data['name'], 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Selamat datang, {{ $data['name'] }}! 👋
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Kelola pre-order sarden kaleng TEFA Anda dari sini.
                </p>
                <div class="flex flex-wrap gap-x-5 gap-y-1 mt-3">
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-building-office class="w-3.5 h-3.5" />
                        {{ $data['organization'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-phone class="w-3.5 h-3.5" />
                        {{ $data['phone'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-envelope class="w-3.5 h-3.5" />
                        {{ $data['email'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-calendar class="w-3.5 h-3.5" />
                        Member sejak {{ $data['member_since'] }}
                    </span>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
