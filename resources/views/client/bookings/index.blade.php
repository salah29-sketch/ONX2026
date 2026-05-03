@extends('client.layout')

@section('title', 'حجوزاتي - بوابة العملاء')

@push('styles')
<style>
.booking-card {
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    background: #fff;
    padding: 18px 20px;
    margin-bottom: 14px;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.booking-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.08); }
.booking-card.event { border-inline-start: 4px solid var(--event-primary); background: #fffbeb; }
.booking-card.event:hover { border-color: var(--event-primary); }
.booking-card.ads   { border-inline-start: 4px solid var(--ads-primary);   background: #eff6ff; }
.booking-card.ads:hover   { border-color: var(--ads-primary); }

.booking-type-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    background: var(--event-soft);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.booking-type-icon.ads { background: var(--ads-soft); }
.booking-body { flex: 1; min-width: 0; }
.booking-card-id   { font-size: 1rem; font-weight: 900; color: #1f2937; }
.booking-card-meta { font-size: 12px; color: #6b7280; margin-top: 3px; }

.booking-progress-bar  { height: 4px; background: #f3f4f6; border-radius: 2px; margin-top: 10px; overflow: hidden; }
.booking-progress-fill         { height: 100%; border-radius: 2px; background: linear-gradient(90deg,#f59e0b,#fbbf24); transition: width .6s ease; }
.booking-progress-fill.ads     { background: linear-gradient(90deg,#3b82f6,#60a5fa); }

.booking-card-status {
    padding: 6px 12px; border-radius: 999px;
    font-size: 11px; font-weight: 800; flex-shrink: 0;
}
.booking-card-status.completed              { background: #dcfce7; color: #166534; }
.booking-card-status.confirmed,
.booking-card-status.in_progress            { background: #fef3c7; color: #b45309; }
.booking-card-status.new,
.booking-card-status.unconfirmed            { background: #f3f4f6; color: #4b5563; }
.booking-card-status.cancelled              { background: #fee2e2; color: #b91c1c; }

.empty-bookings {
    text-align: center; padding: 48px 24px;
    border-radius: 20px; border: 1px solid #e5e7eb;
    background: #fff; color: #6b7280;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.empty-bookings .icon { font-size: 48px; margin-bottom: 16px; opacity: .6; }

/* Dark mode */
.client-portal-dark .booking-card            { background: #151b25 !important; border-color: rgba(255,255,255,.1) !important; }
.client-portal-dark .booking-card.event      { background: rgba(245,158,11,.08) !important; border-inline-start-color: var(--event-primary) !important; }
.client-portal-dark .booking-card.ads        { background: rgba(59,130,246,.08)  !important; border-inline-start-color: var(--ads-primary)   !important; }
.client-portal-dark .booking-type-icon       { background: rgba(245,166,35,.12) !important; }
.client-portal-dark .booking-type-icon.ads   { background: rgba(59,130,246,.12) !important; }
.client-portal-dark .booking-card-id         { color: #fff !important; }
.client-portal-dark .booking-card-meta       { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .booking-progress-bar    { background: #1e2736 !important; }
.client-portal-dark .empty-bookings          { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">📋 حجوزاتي</h1>
    <p class="mt-1 text-sm text-gray-500">كل حجوزاتك مع تتبع المراحل</p>
</div>

@php $clientOrderMap = $clientOrderMap ?? []; @endphp

@forelse($bookings as $b)
    @php
        $step     = $b->statusStep();
        $isEvent  = $b->booking_type === 'event';
        $sDate    = $b->event_date ?? $b->deadline ?? $b->created_at;
        $progress = ($step / 4) * 100;
    @endphp
    <a href="{{ route('client.bookings.show', $b) }}" class="booking-card {{ $isEvent ? 'event' : 'ads' }}">
        <div class="booking-type-icon {{ $isEvent ? '' : 'ads' }}">{{ $isEvent ? '🎪' : '📢' }}</div>
        <div class="booking-body">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <span class="booking-card-id flex items-center gap-1.5 flex-wrap">
                        الطلب {{ $clientOrderMap[$b->id] ?? $b->id }}
                        @if($isEvent)
                            <span class="badge-event text-[10px] px-2 py-0.5">حفلة</span>
                        @else
                            <span class="badge-ads text-[10px] px-2 py-0.5">إعلان</span>
                        @endif
                    </span>
                    <p class="booking-card-meta">
                        {{ $isEvent ? 'تصوير فعاليات' : 'إعلانات' }}
                        · {{ $sDate->format('d/m/Y') }}
                    </p>
                </div>
                <span class="booking-card-status {{ $b->status }}">{{ $b->statusLabel() }}</span>
            </div>
            <div class="booking-progress-bar" title="المرحلة {{ $step }} من 4">
                <div class="booking-progress-fill {{ $isEvent ? '' : 'ads' }}" style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2">المرحلة {{ $step }} من 4 · اضغط لعرض التفاصيل ←</p>
        </div>
    </a>
@empty
    <div class="empty-bookings">
        <div class="icon">📋</div>
        <p class="font-bold text-gray-700">لا توجد حجوزات</p>
        <p class="mt-2 text-sm text-gray-500">عند إتمام حجز من صفحة الحجز في الموقع، ستظهر هنا.</p>
    </div>
@endforelse

<div class="mt-6">{{ $bookings->links() }}</div>
@endsection
