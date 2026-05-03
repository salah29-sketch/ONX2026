@extends('client.layout')

@section('title', 'مدفوعاتي - بوابة العملاء')

@push('styles')
<style>
.panel-pay {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 22px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.panel-pay-head {
    padding: 18px 22px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
}
.panel-pay-title {
    display: flex; align-items: center; gap: 10px;
}
.panel-pay-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--event-soft); display: flex;
    align-items: center; justify-content: center;
    font-size: 20px;
}
.panel-pay-icon.ads { background: var(--ads-soft); }
.panel-pay.event { border-inline-start: 4px solid var(--event-primary); }
.panel-pay.ads   { border-inline-start: 4px solid var(--ads-primary); }
.panel-pay-name { font-weight: 900; color: #1f2937; text-decoration: none; }
.panel-pay-name:hover { color: #b45309; }
.panel-pay-sub  { font-size: 12px; color: #6b7280; margin-top: 2px; }
.invoice-btn {
    border: 1px solid #fde68a;
    background: #fef3c7;
    color: #b45309;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 12px; font-weight: 800;
    text-decoration: none;
    transition: background .2s;
    white-space: nowrap;
}
.invoice-btn:hover { background: #fde68a; }

.panel-pay-body { padding: 20px 22px; }

.pay-summary { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 14px; }
.pay-summary-item { font-size: 13px; }
.pay-summary-item .lbl { color: #6b7280; }
.pay-summary-item .val { font-weight: 800; }

/* Progress bar */
.pay-bar-wrap { background: #f3f4f6; border-radius: 999px; height: 8px; overflow: hidden; margin-bottom: 6px; }
.pay-bar { height: 100%; border-radius: 999px; transition: width .6s ease; }
.pay-bar.green { background: linear-gradient(90deg,#22c55e,#4ade80); }
.pay-bar.amber { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.pay-bar.red   { background: linear-gradient(90deg,#dc2626,#ef4444); }
.pay-pct { font-size: 12px; color: #6b7280; font-weight: 700; margin-bottom: 16px; }

/* Payment rows */
.pay-rows-title {
    font-size: 11px; font-weight: 700;
    color: #9ca3af; text-transform: uppercase;
    letter-spacing: .05em; margin-bottom: 10px;
}
.pay-row {
    display: flex; align-items: center;
    justify-content: space-between; gap: 10px;
    padding: 12px 16px;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 14px; margin-bottom: 8px;
}
.pay-row-info { flex: 1; }
.pay-row-title { font-size: 13px; font-weight: 800; color: #1f2937; }
.pay-row-meta  { font-size: 11px; color: #9ca3af; margin-top: 2px; }
.pay-row-amount{ font-size: 15px; font-weight: 900; color: #16a34a; white-space: nowrap; }

/* Empty */
.empty-state-portal {
    text-align: center; padding: 48px 24px;
    color: #6b7280; background: #fff;
    border: 1px solid #e5e7eb; border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Dark mode */
.client-portal-dark .panel-pay         { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .panel-pay-head    { border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .panel-pay-icon    { background: rgba(245,166,35,.12) !important; }
.client-portal-dark .panel-pay-name    { color: #fff !important; }
.client-portal-dark .panel-pay-name:hover { color: #fbbf24 !important; }
.client-portal-dark .panel-pay-sub     { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .invoice-btn       { background: rgba(245,166,35,.1) !important; border-color: rgba(245,166,35,.25) !important; color: #fbbf24 !important; }
.client-portal-dark .pay-summary-item .lbl { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .pay-summary-item .val { color: #fff !important; }
.client-portal-dark .pay-bar-wrap      { background: #1e2736 !important; }
.client-portal-dark .pay-pct           { color: rgba(255,255,255,.42) !important; }
.client-portal-dark .pay-rows-title    { color: rgba(255,255,255,.3) !important; }
.client-portal-dark .pay-row           { background: #1e2736 !important; border-color: rgba(255,255,255,.05) !important; }
.client-portal-dark .pay-row-title     { color: #fff !important; }
.client-portal-dark .pay-row-meta      { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .empty-state-portal{ background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">💰 المدفوعات</h1>
    <p class="mt-1 text-sm text-gray-500">ملخص المبالغ والدفعات لكل حجز</p>
</div>

@php $clientOrderMap = $clientOrderMap ?? []; @endphp

@forelse($bookings as $booking)
    @php
        $meta    = app(\App\Services\BookingService::class)->getBookingMeta($booking);
        $pct     = $booking->paymentPercent();
        $isEvent = $booking->booking_type === 'event';
        $barClass= $booking->isFullyPaid() ? 'green' : ($booking->remainingAmount() > 0 ? 'red' : 'amber');
    @endphp
    <div class="panel-pay {{ $isEvent ? 'event' : 'ads' }}">
        <div class="panel-pay-head">
            <div class="panel-pay-title">
                <div class="panel-pay-icon {{ $isEvent ? '' : 'ads' }}">{{ $isEvent ? '🎪' : '📢' }}</div>
                <div>
                    <a href="{{ route('client.bookings.show', $booking) }}" class="panel-pay-name">
                        الطلب {{ $clientOrderMap[$booking->id] ?? $booking->id }}
                    </a>
                    <p class="panel-pay-sub">
                        {{ $isEvent ? 'تصوير فعاليات' : 'إعلانات' }}
                        @if($booking->event_date) · {{ $booking->event_date->format('d/m/Y') }} @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('client.bookings.invoice', $booking->id) }}" class="invoice-btn" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i> تحميل الفاتورة
            </a>
        </div>

        <div class="panel-pay-body">
            @if($booking->total_price)
                <div class="pay-summary">
                    <div class="pay-summary-item">
                        <span class="lbl">الإجمالي: </span>
                        <span class="val">{{ number_format($booking->total_price, 0) }} DA</span>
                    </div>
                    <div class="pay-summary-item">
                        <span class="lbl">مدفوع: </span>
                        <span class="val text-green-600">{{ number_format($booking->paidAmount(), 0) }} DA</span>
                    </div>
                    <div class="pay-summary-item">
                        <span class="lbl">متبقي: </span>
                        <span class="val {{ $booking->remainingAmount() > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($booking->remainingAmount(), 0) }} DA
                        </span>
                    </div>
                </div>

                <div class="pay-bar-wrap">
                    <div class="pay-bar {{ $barClass }}" style="width: {{ $pct }}%"></div>
                </div>
                <p class="pay-pct">{{ $pct }}% مسدّد @if($booking->isFullyPaid()) · ✅ مكتمل @endif</p>

                @if($booking->payments->isNotEmpty())
                    <p class="pay-rows-title">سجل الدفعات</p>
                    @foreach($booking->payments as $pay)
                        <div class="pay-row">
                            <div class="pay-row-info">
                                <div class="pay-row-title">{{ $pay->typeLabel() }}</div>
                                <div class="pay-row-meta">{{ $pay->methodLabel() }} · {{ $pay->paid_at->format('d/m/Y') }}</div>
                            </div>
                            <span class="pay-row-amount">+ {{ number_format($pay->amount, 0) }} DA</span>
                        </div>
                    @endforeach
                @endif
            @else
                <p class="text-sm text-gray-500">سيتم تحديد السعر من قِبل الفريق قريباً.</p>
            @endif
        </div>
    </div>
@empty
    <div class="empty-state-portal">
        <div style="font-size:48px;margin-bottom:16px;opacity:.5">💰</div>
        <p class="font-bold text-gray-700">لا توجد حجوزات بعد</p>
        <p class="mt-2 text-sm text-gray-500">عند وجود حجز، ستظهر هنا ملخصات المدفوعات.</p>
    </div>
@endforelse
@endsection
