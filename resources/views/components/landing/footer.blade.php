{{-- Footer Component --}}
<footer class="bg-gray-900 text-gray-400">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/politeknik_logo.png') }}" alt="Polije" class="h-8 w-auto brightness-200">
                    <div class="leading-tight">
                        <span class="text-sm font-semibold text-white">TEFA Canning SIP</span>
                        <span class="block text-[10px] text-gray-500">Politeknik Negeri Jember</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Teaching Factory Fish Canning — memproduksi sarden kaleng berkualitas untuk pembelajaran dan
                    komersial.
                </p>
            </div>

            {{-- Location --}}
            <div>
                <h4 class="text-sm font-semibold text-white mb-3">Lokasi</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    RPVG+PQJ, Jl. Tawang Mangu, Lingkungan Panji, Tegalgede, Kec. Sumbersari, Kabupaten Jember, Jawa
                    Timur 68124
                </p>
                <a href="https://maps.app.goo.gl/YQmQeY4yx1UoHbnT6" target="_blank"
                    class="inline-flex items-center gap-1.5 mt-3 text-xs text-red-400 hover:text-red-300 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Buka di Google Maps
                </a>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-sm font-semibold text-white mb-3">Navigasi</h4>
                <div class="space-y-2">
                    <a href="/#produk" class="block text-xs hover:text-white transition">Produk</a>
                    <a href="/#tentang" class="block text-xs hover:text-white transition">Tentang Kami</a>
                    @auth('customer')
                        <a href="/customer" class="block text-xs hover:text-white transition">Pre-Order</a>
                    @else
                        <a href="/customer/login" class="block text-xs hover:text-white transition">Login Pelanggan</a>
                    @endauth
                    <a href="{{ url('/admin') }}" class="block text-xs hover:text-white transition">Login Admin</a>
                </div>
            </div>

            {{-- Google Maps Widget --}}
            <div>
                <h4 class="text-sm font-semibold text-white mb-3">Peta Lokasi</h4>
                <div class="rounded-lg overflow-hidden border border-gray-700">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4540.858133447697!2d113.72431441119326!3d-8.155649781669197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695f2c7074ec7%3A0x40c147ea5d0ce7c8!2sUnit%20Pengalengan%20Ikan%20Politeknik%20Negeri%20Jember!5e1!3m2!1sen!2sid!4v1771326757344!5m2!1sen!2sid" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Teaching Factory Fish Canning — Politeknik Negeri Jember.
            </p>
            <p class="text-xs text-gray-600">Jl. Mastrip No. 164, Jember, Jawa Timur</p>
        </div>
    </div>
</footer>
