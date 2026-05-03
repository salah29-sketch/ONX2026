{{-- Non-paid offer section — receives: $offerId, $title, $description, $serviceId, $serviceSlug --}}
<section class="py-16 border-t border-white/10">
    <div class="max-w-3xl mx-auto text-center px-6">
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold text-white/70 backdrop-blur">
            <span class="h-2 w-2 rounded-full bg-purple-500"></span>
            حسب الطلب
        </div>

        <h2 class="text-3xl font-black text-white mb-4">{{ $title }}</h2>
        @if($description)
            <p class="text-white/55 mb-8 text-lg leading-8">{{ $description }}</p>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button type="button"
                    onclick="openSbModal({id: null, offerId: {{ $offerId }}, name: '{{ addslashes($title) }}', price: null, priceText: 'حسب الطلب', needsCalendar: false, isSubscription: false})"
                    class="px-8 py-3.5 bg-orange-500 hover:bg-orange-400 text-black rounded-full font-black text-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)]">
                📅 حجز موعد
            </button>
            <a href="https://wa.me/213540573518?text={{ urlencode('سلام ONX، حاب أستفسر عن ' . $title) }}"
               target="_blank"
               class="px-8 py-3.5 border border-white/15 bg-white/5 text-white rounded-full font-black text-sm transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">
                ✉️ أرسل استفسارك
            </a>
        </div>
    </div>
</section>
