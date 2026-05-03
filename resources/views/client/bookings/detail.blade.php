@extends('client.layout')

@section('title', 'تفاصيل الطلب ' . ($clientOrderNumber ?? $booking->id))

@push('styles')
<style>
:root {
    --gold:  #f59e0b;
    --gold2: #b45309;
    --border: #e5e7eb;
    --muted:  #6b7280;
    --green:  #15803d;
    --ink3:   #f9fafb;
}

.portal-wrap { background: transparent; color: #1f2937; font-family: inherit; padding: 0; direction: rtl; }

/* ─ Back + Header ─ */
.portal-back { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 600; margin-bottom: 24px; transition: color .2s; }
.portal-back:hover { color: #b45309; }
.portal-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid var(--border); }
.portal-title    { font-size: 26px; font-weight: 900; color: #1f2937; margin: 0 0 6px; }
.portal-subtitle { font-size: 14px; color: var(--muted); }

/* ─ Status Badge ─ */
.status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; font-size: 14px; font-weight: 800; white-space: nowrap; }
.status-badge .dot { width: 8px; height: 8px; border-radius: 50%; }
.status-unconfirmed { background: rgba(251,191,36,.12); color: #fbbf24; }
.status-unconfirmed .dot { background: #fbbf24; }
.status-confirmed   { background: rgba(96,165,250,.12); color: #60a5fa; }
.status-confirmed .dot { background: #60a5fa; box-shadow: 0 0 6px #60a5fa; }
.status-in_progress { background: rgba(167,139,250,.12); color: #a78bfa; }
.status-in_progress .dot { background: #a78bfa; animation: pulse 1.5s infinite; }
.status-completed   { background: rgba(34,197,94,.12); color: #22c55e; }
.status-completed .dot { background: #22c55e; }
.status-cancelled   { background: rgba(239,68,68,.12); color: #ef4444; }
.status-cancelled .dot { background: #ef4444; }
.status-new         { background: rgba(148,163,184,.12); color: #94a3b8; }
.status-new .dot    { background: #94a3b8; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }

/* ─ Progress Steps ─ */
.progress-track { background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
.progress-track-title { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 20px; }
.steps { display: flex; align-items: center; gap: 0; position: relative; }
.step { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 10px; position: relative; z-index: 1; }
.step-line { flex: 1; height: 2px; background: var(--border); position: relative; top: -22px; margin: 0 -1px; }
.step-line.done   { background: var(--gold); }
.step-line.active { background: linear-gradient(90deg,var(--gold) 50%,var(--border) 50%); }
.step-circle { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 900; border: 2px solid var(--border); background: var(--ink3); color: var(--muted); transition: all .3s ease; }
.step.done .step-circle   { background: var(--gold); border-color: var(--gold); color: #fff; }
.step.active .step-circle { background: #fffbeb; border-color: var(--gold); color: var(--gold2); box-shadow: 0 0 0 3px rgba(245,158,11,.15); }
.step-label { font-size: 12px; font-weight: 700; color: var(--muted); text-align: center; line-height: 1.4; }
.step.done .step-label, .step.active .step-label { color: #1f2937; }

/* ─ Grid ─ */
.portal-grid { display: grid; grid-template-columns: 1fr 380px; gap: 20px; }
@media(max-width: 900px) { .portal-grid { grid-template-columns: 1fr; } }

/* ─ Panel ─ */
.panel { background: #fff; border: 1px solid var(--border); border-radius: 20px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
.panel-head { display: flex; align-items: center; justify-content: space-between; padding: 18px 20px; border-bottom: 1px solid var(--border); }
.panel-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 800; color: #1f2937; }
.panel-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 15px; }
.panel-body { padding: 20px; }

/* ─ Info Grid ─ */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media(max-width: 500px) { .info-grid { grid-template-columns: 1fr; } }
.info-item { background: var(--ink3); border: 1px solid var(--border); border-radius: 14px; padding: 14px; }
.info-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
.info-value { font-size: 15px; font-weight: 700; color: #1f2937; }

/* ─ Payments ─ */
.pay-amounts { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.pay-amount-box { background: #f9fafb; border: 1px solid var(--border); border-radius: 14px; padding: 14px; text-align: center; }
.pay-amount-label { font-size: 11px; color: var(--muted); font-weight: 700; margin-bottom: 6px; }
.pay-amount-value { font-size: 18px; font-weight: 900; }
.pay-bar-wrap { background: #f3f4f6; border-radius: 999px; height: 8px; overflow: hidden; margin-bottom: 8px; }
.pay-bar { height: 100%; border-radius: 999px; background: linear-gradient(90deg,var(--gold),var(--gold2)); transition: width .6s ease; }
.pay-percent-label { font-size: 12px; color: var(--muted); }
.payment-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }
.payment-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; background: var(--ink3); border: 1px solid var(--border); border-radius: 12px; padding: 12px 14px; }
.payment-row-right { display: flex; flex-direction: column; gap: 3px; }
.payment-row-title { font-size: 13px; font-weight: 700; color: #1f2937; }
.payment-row-meta  { font-size: 11px; color: var(--muted); }
.payment-row-amount{ font-size: 16px; font-weight: 900; color: #15803d; white-space: nowrap; }

/* ─ Files ─ */
.file-list { display: flex; flex-direction: column; gap: 10px; }
.file-row { display: flex; align-items: center; gap: 14px; background: #f9fafb; border: 1px solid var(--border); border-radius: 14px; padding: 14px; text-decoration: none; color: inherit; transition: border-color .2s, box-shadow .2s; }
.file-row:hover { border-color: #fcd34d; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
.file-icon-wrap { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.file-info { flex: 1; }
.file-name { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 3px; }
.file-meta { font-size: 11px; color: var(--muted); }
.file-dl-icon { font-size: 18px; color: var(--muted); transition: color .2s; }
.file-row:hover .file-dl-icon { color: #b45309; }

/* ─ Buttons ─ */
.invoice-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; border-radius: 14px; background: rgba(245,166,35,.1); border: 1px solid rgba(245,166,35,.25); color: var(--gold); font-size: 14px; font-weight: 800; text-decoration: none; transition: background .2s, transform .2s; }
.invoice-btn:hover { background: rgba(245,166,35,.18); transform: translateY(-1px); color: var(--gold); }
.action-row { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 14px; background: #f9fafb; border: 1px solid var(--border); color: #1f2937; text-decoration: none; font-size: 13px; font-weight: 700; transition: all .2s; }
.action-row:hover { border-color: #fcd34d; background: #fef3c7; }

/* ─ Empty ─ */
.empty-state { text-align: center; padding: 24px 16px; color: var(--muted); font-size: 13px; }
.empty-state i { font-size: 28px; display: block; margin-bottom: 8px; }

/* ─ Alerts ─ */
.portal-alert { border-radius: 14px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; font-weight: 700; }
.portal-alert-success { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.2); color: #4ade80; }
.portal-alert-danger  { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2); color: #f87171; }

/* ══════════════════════════════════════════
   DARK MODE
══════════════════════════════════════════ */
.client-portal-dark .portal-back { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .portal-back:hover { color: #fbbf24 !important; }
.client-portal-dark .portal-header { border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .portal-title  { color: #fff !important; }
.client-portal-dark .portal-subtitle { color: rgba(255,255,255,.42) !important; }

.client-portal-dark .progress-track { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .progress-track-title { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .step-circle { background: #1e2736 !important; border-color: rgba(255,255,255,.1) !important; color: rgba(255,255,255,.35) !important; }
.client-portal-dark .step.done .step-circle   { background: #f59e0b !important; border-color: #f59e0b !important; color: #000 !important; }
.client-portal-dark .step.active .step-circle { background: rgba(245,158,11,.15) !important; border-color: #f59e0b !important; color: #fbbf24 !important; box-shadow: 0 0 0 3px rgba(245,158,11,.12) !important; }
.client-portal-dark .step-label { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .step.done .step-label, .client-portal-dark .step.active .step-label { color: #fff !important; }
.client-portal-dark .step-line { background: rgba(255,255,255,.07) !important; }
.client-portal-dark .step-line.done   { background: #f59e0b !important; }
.client-portal-dark .step-line.active { background: linear-gradient(90deg,#f59e0b 50%,rgba(255,255,255,.07) 50%) !important; }

.client-portal-dark .panel { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .panel-head  { border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .panel-title { color: #fff !important; }

.client-portal-dark .info-item  { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .info-label { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .info-value { color: #fff !important; }

.client-portal-dark .pay-amount-box   { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .pay-amount-label { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .pay-bar-wrap     { background: #0c0f14 !important; }
.client-portal-dark .pay-percent-label{ color: rgba(255,255,255,.42) !important; }
.client-portal-dark .payment-row       { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .payment-row-title { color: #fff !important; }
.client-portal-dark .payment-row-meta  { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .payment-row-amount{ color: #4ade80 !important; }

.client-portal-dark .file-row { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .file-row:hover { border-color: rgba(245,166,35,.3) !important; }
.client-portal-dark .file-name { color: #fff !important; }
.client-portal-dark .file-meta { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .file-dl-icon { color: rgba(255,255,255,.3) !important; }
.client-portal-dark .file-row:hover .file-dl-icon { color: #fbbf24 !important; }

.client-portal-dark .action-row { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; color: #fff !important; }
.client-portal-dark .action-row:hover { border-color: rgba(245,166,35,.3) !important; background: rgba(245,166,35,.08) !important; }

.client-portal-dark .empty-state { color: rgba(255,255,255,.35) !important; }
</style>
@endpush

@section('client_content')
<div class="portal-wrap">
    <a href="{{ route('client.bookings') }}" class="portal-back">
        <i class="bi bi-arrow-right"></i> العودة للحجوزات
    </a>

    @if(session('success'))
        <div class="portal-alert portal-alert-success">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="portal-alert portal-alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    {{-- Header --}}
    @php $isEventDetail = $booking->booking_type === 'event'; @endphp
    <div class="portal-header" style="border-inline-start: 4px solid {{ $isEventDetail ? 'var(--event-primary, #f59e0b)' : 'var(--ads-primary, #3b82f6)' }}; padding-inline-start: 16px; background: {{ $isEventDetail ? '#fffbeb' : '#eff6ff' }}; border-radius: 14px; margin-bottom: 24px; border-bottom: none;">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="portal-title" style="font-size: 22px; margin: 0;">الطلب {{ $clientOrderNumber ?? $booking->id }}</h1>
                @if($isEventDetail)
                    <span class="badge-event">🎪 حفلة</span>
                @else
                    <span class="badge-ads">📢 إعلان</span>
                @endif
            </div>
            <p class="portal-subtitle">
                {{ $isEventDetail ? 'تصوير فعاليات' : 'إعلانات' }}
                &nbsp;·&nbsp;
                {{ $booking->created_at->format('d/m/Y') }}
            </p>
        </div>
        <span class="status-badge status-{{ $booking->status }}">
            <span class="dot"></span>
            {{ $booking->statusLabel() }}
        </span>
    </div>

    {{-- Progress --}}
    @php $step = $booking->statusStep(); @endphp
    <div class="progress-track">
        <div class="progress-track-title">مراحل الطلب</div>
        <div class="steps">
            @foreach([1=>'استلام الطلب', 2=>'تأكيد الحجز', 3=>'قيد التنفيذ', 4=>'مكتمل ✓'] as $s => $label)
                <div class="step {{ $step >= $s ? ($step > $s ? 'done' : 'active') : '' }}">
                    <div class="step-circle">
                        @if($step > $s)<i class="bi bi-check-lg"></i>@else{{ $s }}@endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
                @if($s < 4)
                    <div class="step-line {{ $step > $s ? 'done' : ($step === $s ? 'active' : '') }}"></div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="rounded-xl border border-amber-200 bg-amber-50/50 px-4 py-3 text-sm text-amber-800 mb-6">
        <span class="font-bold">معلومة التسليم:</span> {{ $booking->deliveryInfoText() }}
    </div>

    <div class="portal-grid">
        {{-- العمود الأيسر --}}
        <div>
            {{-- تفاصيل الحجز --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <div class="panel-icon" style="background:rgba(96,165,250,.12);color:#60a5fa;">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        تفاصيل الحجز
                    </div>
                </div>
                <div class="panel-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">الاسم</div>
                            <div class="info-value">{{ $booking->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">الهاتف</div>
                            <div class="info-value">{{ $booking->phone }}</div>
                        </div>
                        @if($booking->event_date)
                            <div class="info-item">
                                <div class="info-label">تاريخ الفعالية</div>
                                <div class="info-value">{{ $booking->event_date?->format('d/m/Y') ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">المكان</div>
                                <div class="info-value">{{ $meta['locationName'] ?? '—' }}</div>
                            </div>
                        @endif
                        @if(!empty($booking->business_name))
                            <div class="info-item">
                                <div class="info-label">اسم النشاط</div>
                                <div class="info-value">{{ $booking->business_name ?? '—' }}</div>
                            </div>
                        @endif
                        @if($booking->deadline)
                            <div class="info-item">
                                <div class="info-label">الموعد النهائي</div>
                                <div class="info-value">{{ $booking->deadline?->format('d/m/Y') ?? '—' }}</div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="info-label">الباقة</div>
                            <div class="info-value">{{ $meta['packageName'] ?? '—' }}</div>
                        </div>
                        @if($booking->isMonthlySubscription() && $booking->subscription)
                            <div class="info-item" style="grid-column:1/-1;">
                                <div class="info-label">اشتراك شهري</div>
                                <div class="info-value">
                                    <a href="{{ route('client.subscriptions') }}" class="text-amber-600 font-bold hover:underline">عرض الاشتراك وتجديده ←</a>
                                </div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="info-label">سعر الباقة</div>
                            <div class="info-value">
                                {{ $meta['packagePrice'] ? number_format($meta['packagePrice'], 0) . ' DA' : '—' }}
                            </div>
                        </div>
                    </div>
                    @if($booking->notes)
                        <div class="info-item" style="margin-top:12px;">
                            <div class="info-label">ملاحظات</div>
                            <div class="info-value" style="font-size:13px;font-weight:500;line-height:1.7;">
                                {{ $booking->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- الملفات --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <div class="panel-icon" style="background:rgba(96,165,250,.12);color:#60a5fa;">
                            <i class="bi bi-folder2-fill"></i>
                        </div>
                        ملفاتك
                    </div>
                    @if($booking->visibleFiles->isNotEmpty())
                        <span style="font-size:12px;color:var(--muted);">{{ $booking->visibleFiles->count() }} ملف</span>
                    @endif
                </div>
                <div class="panel-body">
                    @if($booking->visibleFiles->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-folder2"></i>
                            لا توجد ملفات متاحة حالياً
                        </div>
                    @else
                        <div class="file-list">
                            @foreach($booking->visibleFiles as $file)
                                <a href="{{ route('client.files.download', $file->id) }}" class="file-row" download>
                                    <div class="file-icon-wrap" style="background:{{ $file->typeColor() }}1a;color:{{ $file->typeColor() }};">
                                        <i class="bi {{ $file->typeIcon() }}"></i>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">{{ $file->label }}</div>
                                        <div class="file-meta">
                                            {{ strtoupper($file->type) }}
                                            @if($file->size) · {{ $file->humanSize() }} @endif
                                        </div>
                                    </div>
                                    <i class="bi bi-download file-dl-icon"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if($booking->photos->isNotEmpty())
                        @php
                            $selCount = auth('client')->user()->selectedPhotos()
                                ->whereIn('booking_photo_id', $booking->photos->pluck('id'))
                                ->count();
                        @endphp
                        @if($selCount > 0)
                            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
                                <form action="{{ route('client.project-photos.zip', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="invoice-btn" style="background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.25);color:#fbbf24;">
                                        <i class="bi bi-file-zip-fill"></i>
                                        تحميل {{ $selCount }} صورة مميزة (ZIP)
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- العمود الأيمن --}}
        <div>
            {{-- المدفوعات --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <div class="panel-icon" style="background:rgba(245,166,35,.12);color:var(--gold);">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        المدفوعات
                    </div>
                </div>
                <div class="panel-body">
                    @if($booking->total_price)
                        <div class="pay-amounts">
                            <div class="pay-amount-box">
                                <div class="pay-amount-label">الإجمالي</div>
                                <div class="pay-amount-value" style="color:#1f2937;">
                                    {{ number_format($booking->total_price, 0) }}
                                    <span style="font-size:10px;font-weight:700;color:var(--muted);">DA</span>
                                </div>
                            </div>
                            <div class="pay-amount-box">
                                <div class="pay-amount-label">مدفوع</div>
                                <div class="pay-amount-value" style="color:var(--green);">
                                    {{ number_format($booking->paidAmount(), 0) }}
                                    <span style="font-size:10px;font-weight:700;color:var(--muted);">DA</span>
                                </div>
                            </div>
                            <div class="pay-amount-box">
                                <div class="pay-amount-label">متبقي</div>
                                <div class="pay-amount-value" style="color:{{ $booking->remainingAmount() > 0 ? '#ef4444' : '#22c55e' }};">
                                    {{ number_format($booking->remainingAmount(), 0) }}
                                    <span style="font-size:10px;font-weight:700;color:var(--muted);">DA</span>
                                </div>
                            </div>
                        </div>
                        <div class="pay-bar-wrap">
                            <div class="pay-bar" style="width:{{ $booking->paymentPercent() }}%;"></div>
                        </div>
                        <div class="pay-percent-label">
                            {{ $booking->paymentPercent() }}% مسدّد
                            @if($booking->isFullyPaid())
                                · <span style="color:var(--green);">✅ مكتمل</span>
                            @endif
                        </div>

                        @if($booking->payments->isNotEmpty())
                            <div class="payment-list" style="margin-top:16px;">
                                @foreach($booking->payments as $pay)
                                    <div class="payment-row">
                                        <div class="payment-row-right">
                                            <div class="payment-row-title">{{ $pay->typeLabel() }}</div>
                                            <div class="payment-row-meta">
                                                {{ $pay->methodLabel() }}
                                                @if($pay->reference) · {{ $pay->reference }} @endif
                                                · {{ $pay->paid_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="payment-row-amount">+ {{ number_format($pay->amount, 0) }} DA</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state" style="margin-top:12px;">
                                <i class="bi bi-receipt"></i>
                                لا توجد دفعات مسجلة بعد
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bi bi-hourglass-split"></i>
                            سيتم تحديد السعر من قِبل الفريق قريباً
                        </div>
                    @endif
                </div>
            </div>

            {{-- الفاتورة --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <div class="panel-icon" style="background:rgba(239,68,68,.12);color:#f87171;">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        الفاتورة
                    </div>
                </div>
                <div class="panel-body" style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('client.bookings.booking-pdf', $booking->id) }}" class="invoice-btn" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> تحميل PDF الحجز
                    </a>
                    <a href="{{ route('client.bookings.invoice', $booking->id) }}" class="invoice-btn" target="_blank"
                       style="background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.2);color:#f87171;">
                        <i class="bi bi-download"></i> تحميل الفاتورة PDF
                    </a>
                    <a href="{{ route('client.bookings.summary', $booking->id) }}"
                       class="invoice-btn"
                       style="background:rgba(96,165,250,.1);border-color:rgba(96,165,250,.25);color:#60a5fa;"
                       target="_blank">
                        <i class="bi bi-printer"></i> طباعة ملخص الحجز
                    </a>
                    <p style="font-size:11px;color:var(--muted);text-align:center;line-height:1.7;">
                        تتضمن الفاتورة تفاصيل الحجز وسجل المدفوعات الكاملة
                    </p>
                </div>
            </div>

            {{-- إجراءات سريعة --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <div class="panel-icon" style="background:rgba(34,197,94,.12);color:#4ade80;">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        إجراءات سريعة
                    </div>
                </div>
                <div class="panel-body" style="display:flex;flex-direction:column;gap:10px;">
                    @if($booking->photos->isNotEmpty())
                        <a href="{{ route('client.project-photos.booking', $booking->id) }}" class="action-row">
                            <i class="bi bi-images" style="color:#60a5fa;"></i>
                            استعراض صور المشروع
                            <span style="margin-right:auto;font-size:11px;color:var(--muted);">
                                {{ $booking->photos->count() }} صورة
                            </span>
                        </a>
                    @endif
                    <a href="{{ route('client.messages') }}" class="action-row">
                        <i class="bi bi-chat-dots-fill" style="color:#a78bfa;"></i>
                        إرسال رسالة للفريق
                    </a>
                    @if($booking->status === 'completed')
                        <a href="{{ route('client.review.create') }}" class="action-row"
                           style="background:rgba(245,166,35,.08);border-color:rgba(245,166,35,.2);color:var(--gold);">
                            <i class="bi bi-star-fill"></i>
                            أضف رأيك عن التجربة
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
