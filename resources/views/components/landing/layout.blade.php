<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'TEFA Canning SIP — Politeknik Negeri Jember' }}</title>
    <meta name="description" content="{{ $description ?? 'Produk sarden kaleng berkualitas dari Teaching Factory Politeknik Negeri Jember.' }}">
    <link rel="icon" href="{{ asset('images/politeknik_logo_red.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:'#fef2f2',100:'#fee2e2',200:'#fecaca',300:'#fca5a5',
                            400:'#f87171',500:'#ef4444',600:'#dc2626',700:'#b91c1c',
                            800:'#991b1b',900:'#7f1d1d',950:'#450a0a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .fade-in{animation:fadeIn .8s ease-out forwards;opacity:0}
        .fade-in-delay-1{animation-delay:.15s}.fade-in-delay-2{animation-delay:.3s}.fade-in-delay-3{animation-delay:.45s}
        @keyframes fadeIn{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .product-card:hover .product-img{transform:scale(1.04)}
    </style>
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    <x-landing.navbar />

    <main>
        {{ $slot }}
    </main>

    <x-landing.footer />

</body>
</html>
