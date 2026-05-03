{{--
    Summary Box Partial
    @param array $pricing
    @param string|null $packageName
    @param string|null $eventDate
    @param string|null $startTime
    @param string|null $endTime
    @param array|null $promoResult
    @param bool $showDeposit (default false)
    @param bool $isReview (default false) — full review mode with contact info
    @param string|null $name, $phone, $email — only used in review mode
--}}
@php
    $showDeposit = $showDeposit ?? false;
    $isReview = $isReview ?? false;
@endphp
<div class="bg-white/[0.04] backdrop-blur-md border border-white/5 rounded-2xl p-5 md:p-6 shadow-xl {{ !$isReview ? 'mt-6' : '' }}">
    @if($isReview)
        <div class="text-lg font-bold text-white mb-6 border-b border-white/10 pb-4">ملخص الحجز</div>
    @endif

    <div class="space-y-3">
        {{-- Package --}}
        @if(!empty($packageName))
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">الباقة</span>
                <span class="font-bold text-white">{{ $packageName }}</span>
            </div>
        @endif

        {{-- Date --}}
        @isset($eventDate)
            @if($eventDate)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-white/60">التاريخ</span>
                    <span class="font-bold text-white">{{ \Carbon\Carbon::parse($eventDate)->translatedFormat('l، j F Y') }}</span>
                </div>
            @endif
        @endisset

        {{-- Time --}}
        @if(!empty($startTime) && !empty($endTime))
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">الوقت</span>
                <span class="font-bold text-white" dir="ltr">{{ $startTime }} — {{ $endTime }}</span>
            </div>
        @endif

        {{-- Location (review mode) --}}
        @if($isReview)
            @isset($venueName)
                @if($venueName)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-white/60">القاعة</span>
                        <span class="font-bold text-white">{{ $venueName }}</span>
                    </div>
                @endif
            @endisset
            @isset($wilayaName)
                @if($wilayaName)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-white/60">الولاية</span>
                        <span class="font-bold text-white">{{ $wilayaName }}</span>
                    </div>
                @endif
            @endisset
        @endif

        <div class="h-px bg-white/10 my-4"></div>

        {{-- Pricing breakdown --}}
        @if(($pricing['base'] ?? 0) > 0)
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">السعر الأساسي</span>
                <span class="text-white/80">{{ number_format($pricing['base'], 0) }} دج</span>
            </div>
        @endif
        @if(($pricing['options_cost'] ?? 0) > 0)
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">الخيارات الإضافية</span>
                <span class="text-white/80">{{ number_format($pricing['options_cost'], 0) }} دج</span>
            </div>
        @endif
        @if(($pricing['time_cost'] ?? 0) > 0)
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">رسوم الوقت</span>
                <span class="text-orange-400">{{ number_format($pricing['time_cost'], 0) }} دج</span>
            </div>
        @endif
        @if(($pricing['travel_cost'] ?? 0) > 0)
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">رسوم التنقل</span>
                <span class="text-orange-400">{{ number_format($pricing['travel_cost'], 0) }} دج</span>
            </div>
        @endif

        {{-- Promo discount --}}
        @if(!empty($promoResult) && ($promoResult['valid'] ?? false))
            <div class="flex justify-between items-center text-sm">
                <span class="text-green-400 font-bold">الخصم</span>
                <span class="text-green-400 font-bold">-{{ number_format($promoResult['discount_amount'], 0) }} دج</span>
            </div>
        @endif

        {{-- Total --}}
        <div class="h-px bg-white/10 my-4"></div>
        <div class="flex justify-between items-center">
            <span class="font-bold text-white">الإجمالي</span>
            <strong class="font-syne font-bold text-2xl text-orange-500">
                {{ number_format(
                    (!empty($promoResult) && ($promoResult['valid'] ?? false))
                        ? $promoResult['final_price']
                        : ($pricing['total'] ?? 0),
                    0
                ) }} <span class="text-base text-orange-400/80">دج</span>{{ !empty($billingLabel) ? ' ' . $billingLabel : '' }}
            </strong>
        </div>

        {{-- Deposit --}}
        @if($showDeposit && ($pricing['deposit'] ?? 0) > 0)
            <div class="bg-white/[0.04] border border-orange-500/20 rounded-xl p-4 mt-6 text-center">
                <div class="text-xs text-white/60 mb-1">العربون المطلوب لتأكيد الحجز</div>
                <div class="font-syne font-bold text-xl text-orange-500">{{ number_format($pricing['deposit'], 0) }} دج</div>
            </div>
        @endif

        {{-- Contact info (review mode) --}}
        @if($isReview)
            <div class="h-px bg-white/10 my-6"></div>
            <div class="text-sm font-bold text-white mb-4">معلومات الاتصال</div>
            @isset($name)
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="text-white/60">الاسم</span>
                    <span class="font-bold text-white">{{ $name }}</span>
                </div>
            @endisset
            @isset($phone)
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="text-white/60">الهاتف</span>
                    <span class="font-bold text-white" dir="ltr">{{ $setup ?? '' }}{{ $phone }}</span>
                </div>
            @endisset
            @isset($email)
                @if($email)
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-white/60">البريد</span>
                        <span class="font-bold text-white">{{ $email }}</span>
                    </div>
                @endif
            @endisset
        @endif
    </div>
</div>
