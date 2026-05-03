<section class="mx-auto max-w-4xl px-6 py-20 lg:px-8">
    <div class="mb-12 text-center">
        <p class="mb-3 text-xs font-extrabold uppercase tracking-[0.25em] text-orange-400">أسئلة شائعة</p>
        <h2 class="text-3xl font-black sm:text-4xl">كل ما تريد معرفته</h2>
    </div>

    <div class="space-y-3" x-data="{ open: null }">
        @foreach($faqs as $i => $faq)
        <div class="rounded-[16px] border border-white/10 bg-white/[0.02] overflow-hidden">
            <button
                @click="open = open === {{ $i }} ? null : {{ $i }}"
                class="w-full flex items-center justify-between px-6 py-4 text-right transition hover:bg-white/[0.02]">
                <span class="text-sm font-black text-white">{{ $faq->question }}</span>
                <svg class="w-4 h-4 text-orange-400 shrink-0 transition-transform duration-200"
                     :class="open === {{ $i }} ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open === {{ $i }}"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="px-6 pb-5">
                <p class="text-sm leading-8 text-white/55">{{ $faq->answer }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
