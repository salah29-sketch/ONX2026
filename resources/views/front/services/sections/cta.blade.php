{{-- CTA Section — receives: $title, $description, $bookingUrl, $whatsappUrl --}}
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="relative overflow-hidden rounded-[34px] border border-orange-500/20 bg-gradient-to-br from-orange-500/12 via-white/5 to-white/5 p-8 shadow-[0_30px_90px_rgba(0,0,0,0.4)] sm:p-10">
        <div class="absolute -left-24 top-1/2 h-52 w-52 -translate-y-1/2 rounded-full bg-orange-500/10 blur-3xl"></div>
        <div class="absolute -right-16 top-0 h-36 w-36 rounded-full bg-white/5 blur-3xl"></div>

        <div class="relative z-10 grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="text-center lg:text-right">
                <h3 class="text-3xl font-black">{{ $title }}</h3>
                <p class="mt-4 text-sm leading-8 text-white/70 sm:text-base">
                    {{ $description }}
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                @if(!empty($contactOnly) && $contactOnly)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                       class="inline-flex rounded-full bg-orange-500 px-6 py-3 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.35)]">
                        واتساب
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">
                        نموذج التواصل
                    </a>
                @else
                    <a href="{{ $bookingUrl }}"
                       class="inline-flex rounded-full bg-orange-500 px-6 py-3 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.35)]">
                        صفحة الحجز
                    </a>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                       class="inline-flex rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">
                        واتساب
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
