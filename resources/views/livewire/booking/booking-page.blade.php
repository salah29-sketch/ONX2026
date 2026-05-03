<div class="max-w-4xl mx-auto">

@if($bookingComplete)
    {{-- ══ CONFIRMATION ══ --}}
    <div class="bg-white/[0.03] backdrop-blur-md border border-white/10 rounded-2xl p-8 md:p-12 text-center max-w-lg mx-auto shadow-2xl animate-fade-in-up">
        <div class="w-20 h-20 mx-auto bg-green-500/10 border-2 border-green-500 text-green-500 rounded-full flex items-center justify-center text-3xl mb-6 shadow-[0_0_30px_rgba(34,197,94,0.2)]">✓</div>
        <div class="font-syne font-bold text-2xl lg:text-3xl mb-2 text-white">تم استلام حجزك!</div>
        <div class="inline-block bg-white/[0.04] border border-orange-500/30 text-orange-400 font-syne font-bold text-lg px-6 py-2 rounded-full mb-6 tracking-wider">#{{ str_pad($bookingId, 4, '0', STR_PAD_LEFT) }}</div>
        <p class="text-white/60 text-sm mb-8 leading-relaxed">سنتواصل معك قريباً لتأكيد التفاصيل. شكراً لاختيارك ONX Edge.</p>

        <div class="bg-orange-950/30 border border-orange-500/30 rounded-xl p-4 mb-8 text-right shadow-inner">
            <div class="text-sm font-bold text-orange-400 mb-1">⚠️ حجزك غير مؤكد بعد</div>
            <div class="text-xs text-orange-300/80 leading-relaxed">لتأكيد الحجز، يرجى دفع العربون. سيقوم فريقنا بمراجعة وتأكيد طلبك خلال 24 ساعة من استلام الدفعة.</div>
        </div>

        @if($generatedPassword)
            <div class="bg-white/[0.04] border border-white/5 rounded-xl p-5 mb-8 text-right">
                <div class="text-xs text-white/40 mb-4 font-semibold tracking-wide">بيانات دخولك لمنطقة العملاء — احتفظ بها الآن</div>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <span class="text-xs text-white/60">البريد الإلكتروني</span>
                    <span class="font-syne font-bold text-sm bg-white/[0.04] px-3 py-1 rounded text-white">{{ $email ?? '' }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-xs text-white/60">كلمة المرور</span>
                    <span class="font-syne font-bold text-sm bg-white/[0.04] px-3 py-1 rounded text-orange-400 tracking-wider">{{ $generatedPassword }}</span>
                </div>
                <div class="text-[10px] text-red-400/80 mt-3 text-center">تظهر مرة واحدة فقط — لن تظهر مجدداً</div>
            </div>
        @elseif($existingAccount)
            <div class="bg-white/[0.04] border border-white/5 rounded-xl p-6 mb-8 text-center flex flex-col items-center">
                <div class="text-3xl mb-3 opacity-80">👤</div>
                <div class="text-sm font-bold text-white/80 mb-1">تم ربط الحجز بحسابك بنجاح</div>
                <div class="text-xs text-white/40">يمكنك الدخول باستخدام بيانات الدخول المعتادة الخاصة بك.</div>
            </div>
        @endif

        <div class="flex flex-col gap-3">
            @auth
                <a href="{{ route('client.dashboard') }}" class="w-full bg-orange-500 hover:bg-orange-400 text-white text-sm font-bold py-3 px-6 rounded-xl transition-colors duration-200 shadow-lg shadow-orange-600/20 text-center">ادخل للوحتك ←</a>
            @else
                <a href="{{ route('login') }}" class="w-full bg-orange-500 hover:bg-orange-400 text-white text-sm font-bold py-3 px-6 rounded-xl transition-colors duration-200 shadow-lg shadow-orange-600/20 text-center">تسجيل الدخول ←</a>
            @endauth
            <a href="{{ route('home') }}" class="w-full bg-transparent border border-white/10 hover:border-white/20 text-white/60 hover:text-white text-sm py-3 px-6 rounded-xl transition-colors duration-200 text-center">العودة للرئيسية</a>
        </div>
    </div>

@elseif(!$selectedCategoryId)
    {{-- ══ STEP 0: CHOOSE TYPE/CATEGORY ══ --}}
    @include('livewire.booking.partials._stepper', [
        'steps' => [['label' => 'النوع'], ['label' => 'الخدمة'], ['label' => 'التفاصيل'], ['label' => 'التأكيد']],
        'currentStep' => 1,
    ])

    <div class="animate-fade-in-up">
        <div class="bg-white/[0.03] backdrop-blur-md border border-white/10 rounded-2xl p-6 md:p-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                <h2 class="text-lg font-bold text-white">اختر نوع الخدمة</h2>
            </div>
            <p class="text-xs text-white/60 mb-8 ms-5">حدد الفئة التي تبحث عنها لبدء تخصيص مشروعك</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category)
                    <button type="button" wire:click="selectCategory({{ $category->id }}, '{{ $category->type instanceof \App\Enums\CategoryType ? $category->type->value : ($category->type ?? 'events') }}')" 
                            class="group relative bg-white/[0.03] border border-white/10 rounded-xl p-5 text-right transition-all duration-300 hover:border-orange-500/50 hover:bg-white/[0.04] overflow-hidden focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 cursor-pointer block w-full">
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @if($category->icon)
                            <div class="text-3xl mb-4 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-transform duration-300 transform origin-right">
                                {{ $category->icon }}
                            </div>
                        @endif
                        <h3 class="text-sm font-bold text-white/80 group-hover:text-white mb-2">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-xs text-white/40 leading-relaxed">{{ Str::limit($category->description, 70) }}</p>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>

@elseif(!$selectedServiceId)
    {{-- ══ STEP 0.5: CHOOSE SERVICE ══ --}}
    @include('livewire.booking.partials._stepper', [
        'steps' => [['label' => 'النوع'], ['label' => 'الخدمة'], ['label' => 'التفاصيل'], ['label' => 'التأكيد']],
        'currentStep' => 2,
    ])

    <div class="animate-fade-in-up">
        <div class="bg-white/[0.03] backdrop-blur-md border border-white/10 rounded-2xl p-6 md:p-8">
            <div class="flex items-center gap-3 mb-2">
                <button type="button" wire:click="goBack" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none cursor-pointer" title="رجوع">
                    &rarr;
                </button>
                <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                <h2 class="text-lg font-bold text-white">اختر الخدمة</h2>
            </div>
            <p class="text-xs text-white/60 mb-8 ms-14">دقة اختيار الخدمة خطوتك الأولى لعمل استثنائي</p>

            @if($services->isEmpty())
                <div class="py-12 border-2 border-dashed border-white/10 rounded-xl text-center flex flex-col items-center">
                    <span class="text-4xl mb-4 opacity-40">🔌</span>
                    <p class="text-sm text-white/60">لا توجد خدمات متاحة حالياً ضمن هذا القسم.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($services as $svc)
                        <button type="button" wire:click="selectService({{ $svc->id }})" 
                                class="group flex items-center justify-between text-right bg-white/[0.03] border border-white/10 hover:border-orange-500/50 rounded-xl p-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 cursor-pointer w-full">
                            <div class="flex items-start gap-4">
                                <div class="w-5 h-5 rounded-full border-2 border-white/15 mt-0.5 group-hover:border-orange-500 group-focus:border-orange-500 transition-colors flex-shrink-0 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-orange-500 opacity-0 group-hover:opacity-50 group-focus:opacity-100 transition-opacity"></div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white/80 group-hover:text-white mb-1 transition-colors">{{ $svc->name }}</h3>
                                    @if($svc->description)
                                        <p class="text-xs text-white/40 line-clamp-2 leading-relaxed text-right">{{ Str::limit($svc->description, 80) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-white/30 group-hover:text-orange-500 transition-colors transform group-hover:translate-x-1 duration-200 text-lg">&larr;</div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@else
    {{-- ══ WIZARD: based on type ══ --}}
    @if($selectedType === 'event')
        @livewire('booking.event-booking-wizard', ['serviceId' => $selectedServiceId], 'event-'.$selectedServiceId)
    @elseif($selectedType === 'appointment')
        @livewire('booking.appointment-booking-wizard', ['serviceId' => $selectedServiceId], 'appt-'.$selectedServiceId)
    @elseif($selectedType === 'subscription')
        @livewire('booking.subscription-booking-wizard', ['serviceId' => $selectedServiceId], 'sub-'.$selectedServiceId)
    @endif
@endif

</div>
