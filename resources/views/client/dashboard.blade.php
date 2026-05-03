@extends('client.layout')

@section('title', 'مساحتك الخاصة - ONX')

@push('styles')
<style>
/* ─── Hero ─── */
.hero-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.hero-card::before {
    content: '';
    position: absolute;
    top: -50%; left: -30%; right: auto;
    width: 80%; height: 80%;
    background: radial-gradient(circle, rgba(245,158,11,.06) 0%, transparent 70%);
    pointer-events: none;
}
.hero-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap; margin-bottom: 4px; }
.hero-name-block { flex-shrink: 0; }
.hero-event-block { text-align: end; min-width: 0; }
.hero-event-block .countdown-boxes { justify-content: flex-end; }
.hero-greeting { font-size: 1rem; color: #6b7280; margin-bottom: 4px; }
.hero-title    { font-size: 1.75rem; font-weight: 900; color: #1f2937; margin: 0 0 16px; }

/* ─── Countdown boxes ─── */
.countdown-boxes { display: flex; gap: 10px; margin: 14px 0; }
.countdown-box {
    flex: 1;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 16px;
    padding: 12px 8px;
    text-align: center;
    max-width: 90px;
}
.countdown-box .num { font-size: 1.6rem; font-weight: 900; color: #b45309; line-height: 1; }
.countdown-box .lbl { font-size: 11px; color: #92400e; margin-top: 4px; font-weight: 700; }
.countdown-done {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 999px;
    background: #dcfce7; color: #166534;
    font-size: 14px; font-weight: 800;
    border: 1px solid #bbf7d0;
}

/* ─── Timeline ─── */
.dash-steps { display: flex; align-items: flex-start; gap: 0; margin-top: 20px; position: relative; }
.dash-step { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; position: relative; z-index: 2; min-width: 0; }
.dash-step-line { flex: 1; height: 2px; min-width: 8px; background: #e5e7eb; position: relative; top: -18px; z-index: 1; align-self: flex-start; margin-top: 18px; }
.dash-step-line.done  { background: #f59e0b !important; }
.dash-step-line.active{ background: #f59e0b !important; }
.dash-step-circle {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2px solid #e5e7eb; background: #f3f4f6;
    color: #9ca3af; font-size: 12px; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    transition: all .3s;
}
.dash-step.done   .dash-step-circle { background: #f59e0b; border-color: #f59e0b; color: #fff; }
.dash-step.active .dash-step-circle { border-color: #f59e0b; color: #fff; background: #f59e0b; }
.dash-step-label  { font-size: 10px; font-weight: 700; color: #6b7280; text-align: center; }
.dash-step.done   .dash-step-label,
.dash-step.active .dash-step-label { color: #1f2937; }

/* ─── Stat Cards ─── */
.stat-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 20px 16px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 4px; height: 100%;
    border-radius: 0 18px 18px 0;
}
.stat-card.green::before { background: #22c55e; }
.stat-card.red::before   { background: #ef4444; }
.stat-card.amber::before { background: #f59e0b; }
.stat-label { font-size: 11px; color: #6b7280; margin-bottom: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; }
.stat-value { font-size: 1.5rem; font-weight: 900; }
.stat-hint  { font-size: 11px; margin-top: 6px; font-weight: 700; }

/* ─── Alert new files ─── */
.alert-new {
    border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;
    font-size: 14px; font-weight: 700;
    display: flex; align-items: center; gap: 12px;
    background: #dcfce7; border: 1px solid #bbf7d0; color: #166534;
}

/* ─── Last message ─── */
.msg-preview {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 16px; padding: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    display: flex; gap: 12px; align-items: flex-start;
}
.msg-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #fef3c7; border: 1px solid #fde68a;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}

/* ─── Booking Cards ─── */
.booking-card-v2 {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    text-decoration: none; color: inherit;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.booking-card-v2:hover { border-color: #fcd34d; box-shadow: 0 4px 12px rgba(0,0,0,.06); }
.booking-type-icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: #fef3c7;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.booking-type-icon.event { background: var(--event-soft); }
.booking-type-icon.ads   { background: var(--ads-soft); }
.booking-card-v2.event { border-inline-start: 3px solid var(--event-primary) !important; }
.booking-card-v2.ads   { border-inline-start: 3px solid var(--ads-primary)   !important; }
.booking-progress-bar { height: 3px; background: #f3f4f6; border-radius: 2px; margin-top: 6px; overflow: hidden; }
.booking-progress-fill        { height: 100%; border-radius: 2px; background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.booking-progress-fill.event  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.booking-progress-fill.ads    { background: linear-gradient(90deg,#3b82f6,#60a5fa); }

/* ─── Company Panel ─── */
.company-panel {
    background: linear-gradient(135deg,#f0fdf4,#ecfdf5);
    border: 1px solid #bbf7d0; border-radius: 20px;
    padding: 20px; margin-bottom: 24px;
}
.company-panel-title { font-size: 14px; font-weight: 900; color: #166534; margin-bottom: 14px; display:flex; align-items:center; gap:8px; }
.company-stat { text-align: center; }
.company-stat .val { font-size: 1.4rem; font-weight: 900; color: #166534; }
.company-stat .lbl { font-size: 11px; color: #4b5563; font-weight: 700; margin-top: 2px; }
.booking-status { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; flex-shrink: 0; }
.booking-status.completed  { background: #dcfce7; color: #166534; }
.booking-status.confirmed,
.booking-status.in_progress{ background: #fef3c7; color: #b45309; }
.booking-status.new,
.booking-status.unconfirmed{ background: #f3f4f6; color: #4b5563; }
.booking-status.cancelled  { background: #fee2e2; color: #b91c1c; }

/* ─── Empty ─── */
.empty-state {
    text-align: center; padding: 32px 20px; color: #6b7280;
    border-radius: 20px; border: 1px solid #e5e7eb;
    background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.empty-state .icon { font-size: 40px; margin-bottom: 12px; opacity: .6; }

/* ─── Dark Mode ─── */
.client-portal-dark .hero-card  { background: linear-gradient(145deg,#151b25,#0c0f14) !important; border-color: rgba(245,166,35,.2) !important; }
.client-portal-dark .hero-greeting { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .hero-title { color: #fff !important; }
.client-portal-dark .countdown-box { background: rgba(245,166,35,.15) !important; border-color: rgba(245,166,35,.25) !important; }
.client-portal-dark .countdown-box .num { color: #fbbf24 !important; }
.client-portal-dark .countdown-box .lbl { color: #d97706 !important; }
.client-portal-dark .countdown-done { background: rgba(34,197,94,.1) !important; border-color: rgba(34,197,94,.2) !important; color: #4ade80 !important; }
.client-portal-dark .stat-card  { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .stat-label { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .stat-hint  { opacity: .8; }
.client-portal-dark .alert-new  { background: rgba(34,197,94,.1) !important; border-color: rgba(34,197,94,.2) !important; color: #4ade80 !important; }
.client-portal-dark .msg-preview{ background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .msg-avatar { background: rgba(245,166,35,.15) !important; border-color: rgba(245,166,35,.25) !important; }
.client-portal-dark .booking-card-v2 { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .booking-card-v2:hover { box-shadow: 0 4px 12px rgba(0,0,0,.3) !important; }
.client-portal-dark .booking-type-icon { background: rgba(245,166,35,.12) !important; }
.client-portal-dark .booking-type-icon.ads { background: rgba(59,130,246,.12) !important; }
.client-portal-dark .company-panel { background: rgba(22,101,52,.12) !important; border-color: rgba(34,197,94,.2) !important; }
.client-portal-dark .company-panel-title { color: #4ade80 !important; }
.client-portal-dark .company-stat .val { color: #4ade80 !important; }
.client-portal-dark .company-stat .lbl { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .booking-progress-bar { background: #1e2736 !important; }
.client-portal-dark .empty-state { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .dash-step-circle { border-color: rgba(255,255,255,.1) !important; background: #1e2736 !important; color: rgba(255,255,255,.42) !important; }
.client-portal-dark .dash-step.done .dash-step-circle  { background: #f59e0b !important; border-color: #f59e0b !important; color: #000 !important; }
.client-portal-dark .dash-step.active .dash-step-circle{ border-color: #f59e0b !important; background: #f59e0b !important; color: #fff !important; }
.client-portal-dark .dash-step-label { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .dash-step.done .dash-step-label,
.client-portal-dark .dash-step.active .dash-step-label { color: #fff !important; }
.client-portal-dark .dash-steps .dash-step-line { background: rgba(255,255,255,.07) !important; }
.client-portal-dark .dash-steps .dash-step-line.done,
.client-portal-dark .dash-steps .dash-step-line.active { background: #f59e0b !important; }
</style>
@endpush

@section('client_content')

{{-- إشعار: ملفات جديدة --}}
@if(!empty($hasNewFilesOrVideo))
<a href="{{ route('client.media', ['filter' => 'videos']) }}" class="block alert-new">
    <span>🎬</span>
    <span>لديك فيديو أو ملفات جاهزة للتحميل — اضغط هنا</span>
</a>
@endif

{{-- Hero Card --}}
<div class="hero-card">
    <div class="hero-top">
        <div class="hero-name-block">
            <p class="hero-greeting">مرحباً،</p>
            @if(!empty($client->is_company) && !empty($client->business_name))
                <h1 class="hero-title">{{ $client->business_name }}</h1>
                <p class="text-sm text-gray-500 -mt-3 mb-2 flex items-center gap-1"><i class="bi bi-building"></i> {{ $client->name }}</p>
            @else
                <h1 class="hero-title">{{ $client->name }}</h1>
            @endif
        </div>

        @if($activeBooking)
            <div class="hero-event-block">
                <p class="text-gray-600 text-sm mb-2">
                    {{ $activeBooking->booking_type === 'event' ? '🎬 تصوير فعاليات' : '📢 إعلانات' }}
                    @if($activeBooking->event_date)
                        · {{ ar_date($activeBooking->event_date, 'l d F') }}
                    @endif
                </p>

                {{-- Countdown: أيام/ساعات/دقائق --}}
                @if($activeBooking->event_date && $activeBooking->event_date->isFuture())
                    <div class="countdown-boxes"
                         x-data="{
                            target: '{{ $activeBooking->event_date->toIso8601String() }}',
                            days: 0, hours: 0, mins: 0,
                            init() {
                                this.calc();
                                setInterval(() => this.calc(), 60000);
                            },
                            calc() {
                                let diff = Math.max(0, new Date(this.target) - new Date());
                                this.days  = Math.floor(diff / 86400000);
                                this.hours = Math.floor((diff % 86400000) / 3600000);
                                this.mins  = Math.floor((diff % 3600000)  / 60000);
                            }
                         }">
                        <div class="countdown-box">
                            <div class="num" x-text="String(days).padStart(2,'0')">00</div>
                            <div class="lbl">يوم</div>
                        </div>
                        <div class="countdown-box">
                            <div class="num" x-text="String(hours).padStart(2,'0')">00</div>
                            <div class="lbl">ساعة</div>
                        </div>
                        <div class="countdown-box">
                            <div class="num" x-text="String(mins).padStart(2,'0')">00</div>
                            <div class="lbl">دقيقة</div>
                        </div>
                    </div>
                @else
                    <div class="countdown-done">✅ {{ $activeBooking->statusLabel() }}</div>
                @endif
            </div>
        @endif
    </div>

    @if($activeBooking)
        {{-- Timeline --}}
        @php $step = $activeBooking->statusStep(); @endphp
        <div class="dash-steps mt-4">
            @foreach([1 => 'استلام', 2 => 'تأكيد', 3 => 'تنفيذ', 4 => 'مكتمل'] as $s => $label)
                <div class="dash-step {{ $step >= $s ? ($step > $s ? 'done' : 'active') : '' }}">
                    <div class="dash-step-circle">
                        @if($step > $s)<i class="bi bi-check-lg"></i>@else{{ $s }}@endif
                    </div>
                    <div class="dash-step-label">{{ $label }}</div>
                </div>
                @if($s < 4)
                    <div class="dash-step-line {{ $step > $s ? 'done' : ($step === $s ? 'active' : '') }}"></div>
                @endif
            @endforeach
        </div>

        <p class="mt-4 rounded-xl border border-amber-200 bg-amber-50/50 px-4 py-2.5 text-sm text-amber-800">
            <span class="font-bold">معلومة التسليم:</span> {{ $activeBooking->deliveryInfoText() }}
        </p>
    @else
        <p class="text-gray-500 text-sm mt-2">مساحتك الخاصة لمتابعة حجوزاتك وملفاتك.</p>
    @endif
</div>

{{-- 3 بطاقات إحصاء محسّنة --}}
@if($activeBooking)
<div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
    @php
        $paidAmt = $activeBooking->paidAmount();
        $remAmt  = $activeBooking->remainingAmount();
        $pct     = $activeBooking->paymentPercent();
    @endphp
    <div class="stat-card green">
        <div class="stat-label">المبلغ المدفوع</div>
        <div class="stat-value text-green-600">{{ number_format($paidAmt, 0) }} <span class="text-xs font-bold text-gray-400">DA</span></div>
        <div class="stat-hint text-green-600">{{ $pct }}% مسدّد</div>
    </div>
    <div class="stat-card {{ $remAmt > 0 ? 'red' : 'green' }}">
        <div class="stat-label">المبلغ المتبقي</div>
        <div class="stat-value {{ $remAmt > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($remAmt, 0) }} <span class="text-xs font-bold text-gray-400">DA</span></div>
        <div class="stat-hint {{ $remAmt > 0 ? 'text-red-500' : 'text-green-500' }}">
            {{ $remAmt > 0 ? 'يرجى إتمام الدفع' : '✓ مكتمل' }}
        </div>
    </div>
    <div class="stat-card amber">
        <div class="stat-label">الصور المرفوعة</div>
        <div class="stat-value text-gray-800">{{ $activeBooking->photos->count() }}</div>
        <div class="stat-hint text-amber-600">
            {{ $activeBooking->photos->count() > 0 ? 'اضغط لاختيار المميزة' : 'لم ترفع بعد' }}
        </div>
    </div>
</div>
@endif

{{-- آخر رسالة --}}
@if(isset($lastMessage))
<div class="msg-preview mb-6">
    <div class="msg-avatar">✉️</div>
    <div class="flex-1 min-w-0">
        <p class="text-xs font-bold text-gray-500 mb-1">آخر رسالة</p>
        <p class="text-gray-700 text-sm line-clamp-2">{{ Str::limit($lastMessage->message, 100) }}</p>
        <a href="{{ route('client.messages') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:underline">
            اذهب للرسائل <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>
@endif

{{-- الاشتراكات الشهرية (باقات الإعلان) ──────────────────────────── --}}
@php $subscriptions = $subscriptions ?? collect(); @endphp
@if($subscriptions->isNotEmpty())
<div class="mb-6">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-black text-gray-800">الاشتراكات</h2>
        <a href="{{ route('client.subscriptions') }}" class="text-sm font-bold text-amber-600 hover:underline">عرض الكل</a>
    </div>
    <div class="space-y-3">
        @foreach($subscriptions as $sub)
            <div class="booking-card-v2" style="pointer-events:auto;">
                <div class="booking-type-icon">📢</div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center gap-2">
                        <span class="font-black text-gray-800 text-sm">{{ $sub->planName() }}</span>
                        <span class="booking-status {{ $sub->status }}">{{ $sub->statusLabel() }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        بدء: {{ $sub->start_date->format('d/m/Y') }}
                        · تجديد قادم: {{ $sub->next_billing_date->format('d/m/Y') }}
                        · {{ $sub->renewalTypeLabel() }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- لوحة الشركات (يظهر فقط للشركات) --}}
@if(!empty($client->is_company))
@php
    $totalSpent = $bookings->sum(fn($b) => $b->paidAmount());
    $activeSubs = $subscriptions->where('status','active')->count();
    $totalBookings = $bookings->count();
@endphp
<div class="company-panel mb-6">
    <p class="company-panel-title"><i class="bi bi-building"></i> ملخص حساب الشركة</p>
    <div class="grid grid-cols-3 gap-4">
        <div class="company-stat">
            <div class="val">{{ number_format($totalSpent, 0) }}</div>
            <div class="lbl">إجمالي المدفوعات (DA)</div>
        </div>
        <div class="company-stat">
            <div class="val">{{ $totalBookings }}</div>
            <div class="lbl">مجموع الحجوزات</div>
        </div>
        <div class="company-stat">
            <div class="val">{{ $activeSubs }}</div>
            <div class="lbl">اشتراكات نشطة</div>
        </div>
    </div>
</div>
@endif

{{-- آخر الحجوزات --}}
<div class="mb-4 flex items-center justify-between">
    <h2 class="text-lg font-black text-gray-800">آخر حجوزاتك</h2>
    @if($bookings->isNotEmpty())
        <a href="{{ route('client.bookings') }}" class="text-sm font-bold text-amber-600 hover:underline">عرض الكل</a>
    @endif
</div>

@php $clientOrderMap = $clientOrderMap ?? []; @endphp

@if($bookings->isNotEmpty())
    <div class="space-y-3">
        @foreach($bookings as $b)
            @php
                $bStep  = $b->statusStep();
                $isEvent= $b->booking_type === 'event';
                $sDate  = $b->event_date ?? $b->deadline ?? $b->created_at;
                $prog   = ($bStep / 4) * 100;
            @endphp
            <a href="{{ route('client.bookings.show', $b) }}" class="booking-card-v2 {{ $isEvent ? 'event' : 'ads' }}">
                <div class="booking-type-icon {{ $isEvent ? 'event' : 'ads' }}">{{ $isEvent ? '🎪' : '📢' }}</div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center gap-2">
                        <span class="flex items-center gap-1.5 font-black text-gray-800 text-sm">
                            الطلب {{ $clientOrderMap[$b->id] ?? $b->id }}
                            @if($isEvent)
                                <span class="badge-event text-[10px] px-2 py-0.5">حفلة</span>
                            @else
                                <span class="badge-ads text-[10px] px-2 py-0.5">إعلان</span>
                            @endif
                        </span>
                        <span class="booking-status {{ $b->status }}">{{ $b->statusLabel() }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $isEvent ? 'تصوير فعاليات' : 'إعلانات' }} · {{ $sDate->format('d/m/Y') }}
                    </p>
                    <div class="booking-progress-bar">
                        <div class="booking-progress-fill {{ $isEvent ? 'event' : 'ads' }}" style="width: {{ $prog }}%"></div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="icon">📋</div>
        <p class="font-bold text-gray-700">لا توجد حجوزات بعد</p>
        <p class="mt-2 text-sm text-gray-500">عند إتمام حجز من الموقع، ستظهر هنا وتستطيع متابعتها.</p>
    </div>
@endif

@endsection
