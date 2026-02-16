{{-- Product Card Component --}}
@props(['name', 'description', 'image', 'badge', 'badgeColor' => 'brand-600', 'tags' => [], 'bgGradient' => 'from-red-50 to-orange-50', 'delay' => '1'])

<div class="product-card group bg-white rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-50 transition-all duration-300 overflow-hidden fade-in fade-in-delay-{{ $delay }}">
    <div class="aspect-square bg-gradient-to-br {{ $bgGradient }} flex items-center justify-center p-8 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $name }}"
             class="product-img h-full w-full object-contain transition-transform duration-500"
             onerror="this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center h-full text-gray-300\'><svg class=\'w-20 h-20\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/></svg><span class=\'mt-3 text-sm font-medium text-gray-400\'>{{ $name }}</span></div>'">
    </div>
    <div class="p-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-[10px] font-bold text-white bg-{{ $badgeColor }} px-2.5 py-1 rounded-full uppercase tracking-wide">{{ $badge }}</span>
            <span class="text-[10px] font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">425gr</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-brand-600 transition">{{ $name }}</h3>
        <p class="mt-2 text-sm text-gray-400 leading-relaxed">{{ $description }}</p>
        @if(count($tags))
        <div class="mt-4 flex items-center gap-3">
            @foreach($tags as $tag)
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ $tag }}
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
