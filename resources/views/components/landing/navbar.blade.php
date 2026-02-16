{{-- Navbar Component --}}
<nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('images/politeknik_logo_red.png') }}" alt="Polije" class="h-9 w-auto">
            <div class="hidden sm:block leading-tight">
                <span class="text-sm font-bold text-brand-700 tracking-tight">TEFA Canning SIP</span>
                <span class="block text-[10px] text-gray-400 font-medium">Politeknik Negeri Jember</span>
            </div>
        </a>

        <div class="flex items-center gap-4 sm:gap-6">
            <a href="/#produk" class="text-sm text-gray-500 hover:text-brand-600 transition font-medium hidden sm:block">Produk</a>
            <a href="/#tentang" class="text-sm text-gray-500 hover:text-brand-600 transition font-medium hidden sm:block">Tentang</a>
            <a href="/#batch" class="text-sm text-gray-500 hover:text-brand-600 transition font-medium hidden sm:block">Info Batch</a>
            @auth('customer')
                <a href="/customer" class="text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition shadow-sm">
                    Pre-Order
                </a>
            @else
                <a href="/customer/login" class="text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition shadow-sm">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
