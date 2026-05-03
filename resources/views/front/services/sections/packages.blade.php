{{-- Packages Section — receives: $sectionId, $title, $subtitle, $cards, $allFeatures, $count --}}
<section id="{{ $sectionId }}" class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="mb-12 text-center">
        <p class="mb-3 text-xs font-extrabold uppercase tracking-[0.25em] text-orange-400">الباقات المتاحة</p>
        <h2 class="text-3xl font-black sm:text-4xl">{{ $title }}</h2>
        @if($subtitle)
        <p class="mx-auto mt-4 max-w-2xl text-sm leading-8 text-white/65 sm:text-base">
            {{ $subtitle }}
        </p>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 items-stretch
                {{ $count === 1 ? 'max-w-md mx-auto' : '' }}
                {{ $count === 2 ? 'sm:grid-cols-2 max-w-3xl mx-auto' : '' }}
                {{ $count >= 3 ? 'sm:grid-cols-2 lg:grid-cols-3' : '' }}">
        @forelse($cards as $card)
            @include('front.services.sections._package-card', ['card' => $card, 'allFeatures' => $allFeatures])
        @empty
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-8 text-center sm:col-span-2 lg:col-span-3">
                <h4 class="text-2xl font-black">لا توجد باقات بعد</h4>
                <p class="mt-3 text-sm leading-7 text-white/65">أضف الباقات من لوحة التحكم.</p>
            </div>
        @endforelse
    </div>

    {{-- Compare trigger button (only visible when Alpine has 2+ items) --}}
    @if(!empty($cards) && ($cards[0]['showCompare'] ?? false))
    <div x-show="compareList.length >= 2"
         x-cloak
         x-transition
         class="mt-10 flex justify-center">
        <button @click="showModal = true"
                class="inline-flex items-center gap-2.5 rounded-full bg-orange-500 px-7 py-3.5 text-sm font-black text-black shadow-[0_0_24px_rgba(249,115,22,0.3)] transition duration-300 hover:bg-orange-400 hover:-translate-y-0.5">
            ⚖️ مقارنة الباقات
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-black/20 text-xs font-black" x-text="compareList.length"></span>
        </button>
    </div>
    @endif
</section>
