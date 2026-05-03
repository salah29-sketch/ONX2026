<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="rounded-[28px] border border-white/10 bg-white/[0.03] p-10 text-center">
        <div class="mb-5 inline-flex items-center justify-center w-14 h-14 rounded-full bg-orange-500/10 border border-orange-500/20">
            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-2xl font-black text-white mb-3">مو متأكد أي باقة تناسبك؟</h2>
        <p class="text-sm leading-8 text-white/55 max-w-lg mx-auto mb-8">
            تواصل معنا مباشرة وسنساعدك تختار الباقة الأمثل حسب مناسبتك وميزانيتك — بدون أي التزام
        </p>

        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ $whatsappUrl }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-full bg-orange-500 hover:bg-orange-400 px-7 py-3.5 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)]">
                واتساب
            </a>
            <button type="button"
                onclick="openSbModal({
                    id: null,
                    offerId: null,
                    name: 'استشارة مجانية',
                    price: null,
                    priceText: 'مجاني',
                    needsCalendar: true,
                    isSubscription: false
                })"
                class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-7 py-3.5 text-sm font-black text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">
                احجز استشارة مجانية
            </button>
        </div>
    </div>
</section>
