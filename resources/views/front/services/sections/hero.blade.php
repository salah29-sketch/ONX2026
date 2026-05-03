{{-- Hero Section — receives: $title, $description, $badge, $bgImage, $travelNote, $bookingUrl --}}
<section class="relative isolate overflow-hidden border-b border-white/10">
    <div class="absolute inset-0 -z-10">
        @if($bgImage)
        <img src="{{ asset($bgImage) }}"
             alt="{{ $title }}"
             class="h-full w-full object-cover opacity-20">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/80 to-[#050505]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(255,106,0,0.14),transparent_28%),radial-gradient(circle_at_20%_80%,rgba(255,106,0,0.06),transparent_26%)]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-6 py-20 text-center lg:px-8 lg:py-24">
        <div class="mx-auto max-w-4xl">
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold text-white/70 opacity-0 backdrop-blur animate-fade-in-up">
                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                {{ $badge }}
            </div>

            <h1 class="text-4xl font-black leading-tight text-white opacity-0 sm:text-5xl lg:text-6xl animate-fade-in-up animate-delay-100">
                {{ $title }}
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-sm leading-8 text-white/70 opacity-0 sm:text-base animate-fade-in-up animate-delay-200">
                {{ $description }}
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 opacity-0 animate-fade-in-up animate-delay-300">
                @if(!empty($contactOnly) && $contactOnly)
                    <a href="{{ $whatsappUrl ?? 'https://wa.me/213540573518' }}" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center rounded-full bg-orange-500 px-7 py-3 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)] active:scale-[0.98]">
                        واتساب
                    </a>
                    <a href="{{ $contactUrl ?? route('contact') }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-7 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10 active:scale-[0.98]">
                        نموذج التواصل
                    </a>
                @else
                    <a href="{{ $packagesAnchor ?? '#packages' }}"
                       class="inline-flex items-center justify-center rounded-full bg-orange-500 px-7 py-3 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)] active:scale-[0.98]">
                        مشاهدة الباقات
                    </a>
                    <a href="{{ $whatsappUrl ?? 'https://wa.me/213540573518' }}" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-7 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10 active:scale-[0.98]">
                        واتساب
                    </a>
                    <a href="{{ $bookingUrl }}"
                       class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-7 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10 active:scale-[0.98]">
                        احجز الآن
                    </a>
                @endif
            </div>

            @if($travelNote)
            <div class="mt-6 opacity-0 animate-fade-in-up animate-delay-400">
                <p class="inline-flex items-center gap-2 border border-orange-500/30 bg-orange-500/10 text-orange-300 text-sm px-4 py-2 rounded-full">
                    <span>🚗</span> {{ $travelNote }}
                </p>
            </div>
            @endif
        </div>
    </div>
</section>
