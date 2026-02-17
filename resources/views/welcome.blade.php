<x-landing.layout>

    {{-- ═══════════════ HERO ═══════════════ --}}
    <section class="relative min-h-screen flex flex-col justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-white to-red-50/40"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-brand-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>

        <div class="relative max-w-6xl mx-auto px-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-brand-50 text-brand-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-brand-100">
                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-pulse"></span>
                    Teaching Factory — Polije
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-[1.1] tracking-tight">
                    Canning SIP
                    <span class="block text-brand-600">Sehat, Lezat & Bergizi</span>
                </h1>

                <p class="mt-5 text-lg text-gray-500 leading-relaxed max-w-lg">
                    Sarden kaleng premium dari ikan lemuru segar, diproduksi oleh Teaching Factory Politeknik Negeri Jember dengan standar mutu terjamin.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#produk" class="inline-flex items-center gap-2 bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-200">
                        Lihat Produk
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <a href="/customer/register" class="inline-flex items-center gap-2 text-brand-600 font-semibold px-6 py-3 rounded-xl border border-brand-200 hover:bg-brand-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        Pre-Order Sekarang
                    </a>
                </div>
            </div>

            {{-- Stats strip --}}
            <div class="mt-16 grid grid-cols-3 gap-6 max-w-md">
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $products->count() }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Varian Produk</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">425gr</div>
                    <div class="text-xs text-gray-400 mt-0.5">Per Kaleng</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">100%</div>
                    <div class="text-xs text-gray-400 mt-0.5">Tanpa Pengawet</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════ PRODUCTS ═══════════════ --}}
    <section id="produk" class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Katalog</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Produk Kami</h2>
                <p class="mt-3 text-gray-400 max-w-md mx-auto">Tiga varian sarden kaleng dengan cita rasa khas Indonesia, diproduksi tanpa bahan pengawet.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <x-landing.product-card
                    name="Sarden SIP Saus Tomat"
                    description="Ikan lemuru dengan saus tomat rempah alami Indonesia. Gurih dan asam yang seimbang, tanpa pengawet."
                    :image="asset('images/products/sarden-saus-tomat.png')"
                    badge="Terlaris"
                    badgeColor="brand-600"
                    :tags="['Halal', 'Sterilisasi Komersial']"
                    bgGradient="from-red-50 to-orange-50"
                    delay="1"
                />

                <x-landing.product-card
                    name="Sarden SIP Asin"
                    description="Varian paling fleksibel — ikan lemuru dan larutan garam. Cocok sebagai bahan dasar masakan atau langsung dinikmati."
                    :image="asset('images/products/sarden-asin.png')"
                    badge="Fleksibel"
                    badgeColor="sky-500"
                    :tags="['Halal', 'Tanpa Pengawet']"
                    bgGradient="from-blue-50 to-cyan-50"
                    delay="2"
                />

                <x-landing.product-card
                    name="Sarden SIP Saus Cabai"
                    description="Ikan lemuru dengan saus cabai khas Nusantara. Pedasnya pas, tidak mengalahkan rasa alami ikan."
                    :image="asset('images/products/sarden-saus-cabai.png')"
                    badge="Pedas"
                    badgeColor="orange-500"
                    :tags="['Halal', 'Aman & Tahan Lama']"
                    bgGradient="from-red-50 to-rose-50"
                    delay="3"
                />
            </div>
        </div>
    </section>

    {{-- ═══════════════ BATCH NEWS ═══════════════ --}}
    <section id="batch" class="py-20 bg-gradient-to-b from-white to-gray-50/50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Info Terbaru</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Batch Produksi</h2>
                <p class="mt-3 text-gray-400 max-w-md mx-auto">Informasi batch produksi yang sedang dibuka untuk pre-order.</p>
            </div>

            @if($batches->isEmpty())
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Batch Terbuka</h3>
                    <p class="mt-2 text-sm text-gray-400 max-w-sm mx-auto">Saat ini belum ada batch produksi yang dibuka untuk pre-order. Pantau terus halaman ini untuk informasi terbaru.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($batches as $batch)
                        <div class="bg-white rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-lg hover:shadow-brand-50 transition-all duration-300 overflow-hidden fade-in fade-in-delay-{{ $loop->iteration }}">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            Dibuka
                                        </span>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900">{{ $batch->name }}</h3>

                                @if($batch->event_name)
                                    <p class="mt-1 text-sm text-brand-600 font-medium">{{ $batch->event_name }}</p>
                                @endif

                                @if($batch->description)
                                    <p class="mt-2 text-sm text-gray-400 leading-relaxed line-clamp-2">{{ $batch->description }}</p>
                                @endif

                                <div class="mt-4 flex items-center gap-4">
                                    @if($batch->event_date)
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            {{ $batch->event_date->format('d M Y') }}
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                        {{ $batch->orders()->count() }} pesanan
                                    </div>
                                </div>

                                <a href="/customer/register" class="mt-5 w-full inline-flex items-center justify-center gap-2 bg-brand-50 text-brand-700 hover:bg-brand-100 font-semibold px-4 py-2.5 rounded-xl transition text-sm">
                                    Pre-Order Batch Ini
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════ SNI DISCLAIMER ═══════════════ --}}
    <section class="py-12 bg-amber-50/60 border-y border-amber-100">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex gap-4 items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-800">Disclaimer SNI</h4>
                    <p class="mt-1 text-sm text-amber-700 leading-relaxed">
                        Produk TEFA Canning diproduksi dalam lingkungan pembelajaran Teaching Factory. Produk telah melalui proses quality control standar dan sterilisasi komersial, namun mungkin memiliki variasi minor yang tidak memengaruhi kualitas dan keamanan pangan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════ ABOUT ═══════════════ --}}
    <section id="tentang" class="py-24 bg-gray-50/50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Tentang Kami</span>
                    <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Teaching Factory<br>
                        <span class="text-brand-600">Fish Canning Polije</span>
                    </h2>
                    <p class="mt-5 text-gray-500 leading-relaxed">
                        Teaching Factory (TEFA) Canning adalah unit produksi pembelajaran Politeknik Negeri Jember yang memproduksi sarden kaleng berkualitas. Seluruh proses produksi dilakukan oleh mahasiswa di bawah bimbingan dosen ahli.
                    </p>
                    <div class="mt-8 space-y-4">
                        @foreach([
                            'Diproduksi dengan proses <strong>sterilisasi komersial</strong> sesuai standar industri',
                            '<strong>Tanpa bahan pengawet</strong> — hanya bahan alami pilihan',
                            'Menggunakan <strong>ikan lemuru segar</strong> berkualitas tinggi',
                            'Sistem <strong>Pre-Order berbasis Batch</strong> menjamin kesegaran produk',
                        ] as $feature)
                            <div class="flex gap-3 items-start">
                                <div class="mt-0.5 w-6 h-6 bg-brand-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-brand-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                                <p class="text-sm text-gray-600">{!! $feature !!}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center">
                    <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 w-full max-w-sm">
                        <img src="{{ asset('images/3_logo_in_1.png') }}" alt="Logo TEFA Polije" class="w-full h-auto">
                    </div>
                    <p class="mt-4 text-xs text-gray-400 text-center">Teaching Factory &middot; Fish Canning &middot; Politeknik Negeri Jember</p>
                </div>
            </div>
        </div>
    </section>

</x-landing.layout>
