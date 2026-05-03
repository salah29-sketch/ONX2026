@extends('client.layout')

@section('title', 'صور مشروعي - بوابة العملاء')

@push('styles')
<style>
.photos-header { margin-bottom: 24px; }
.photos-header h1 { font-size: 22px; font-weight: 900; color: #1f2937; }
.photos-header p  { font-size: 13px; color: #6b7280; margin-top: 6px; line-height: 1.7; }

.selected-banner {
    display: flex; align-items: center; gap: 10px;
    background: #fef3c7; border: 1px solid #fde68a;
    border-radius: 16px; padding: 12px 18px;
    margin-bottom: 20px;
    font-size: 13px; font-weight: 700; color: #b45309;
}
.selected-banner .count {
    background: #f59e0b; color: #fff;
    border-radius: 999px; padding: 2px 10px;
    font-size: 12px; font-weight: 900;
}

.booking-photo-card {
    display: flex; align-items: center; gap: 14px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 16px 20px;
    margin-bottom: 12px;
    text-decoration: none; color: inherit;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.booking-photo-card:hover { border-color: #fcd34d; box-shadow: 0 4px 12px rgba(0,0,0,.06); }

.booking-photo-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: #fef3c7;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.booking-photo-info { flex: 1; }
.booking-photo-id   { font-size: 15px; font-weight: 900; color: #1f2937; }
.booking-photo-date { font-size: 12px; color: #9ca3af; margin-top: 3px; }
.booking-photo-count {
    font-size: 12px; font-weight: 700;
    background: #f3f4f6; color: #6b7280;
    border-radius: 999px; padding: 4px 12px;
    flex-shrink: 0;
}

.empty-photos {
    text-align: center; padding: 48px 24px;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 20px; color: #6b7280;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Dark mode */
.client-portal-dark .photos-header h1   { color: #fff !important; }
.client-portal-dark .photos-header p    { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .selected-banner    { background: rgba(245,166,35,.12) !important; border-color: rgba(245,166,35,.25) !important; color: #fcd34d !important; }
.client-portal-dark .booking-photo-card { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .booking-photo-card:hover { border-color: rgba(245,166,35,.3) !important; }
.client-portal-dark .booking-photo-icon { background: rgba(245,166,35,.12) !important; }
.client-portal-dark .booking-photo-id   { color: #fff !important; }
.client-portal-dark .booking-photo-date { color: rgba(255,255,255,.35) !important; }
.client-portal-dark .booking-photo-count{ background: #1e2736 !important; color: rgba(255,255,255,.5) !important; }
.client-portal-dark .empty-photos       { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
</style>
@endpush

@section('client_content')

<div class="photos-header">
    <h1>📷 صور مشروعي</h1>
    <p>اختر مشروعك لمشاهدة الصور وتحميلها، أو تحديد حتى 200 صورة كمميزة للطباعة.</p>
</div>

@if($selectedCount > 0)
    <div class="selected-banner">
        <span>❤️</span>
        <span>الصور المميزة المختارة</span>
        <span class="count">{{ $selectedCount }} / 200</span>
    </div>
@endif

@forelse($bookings as $b)
    <a href="{{ route('client.project-photos.booking', $b) }}" class="booking-photo-card">
        <div class="booking-photo-icon">🎬</div>
        <div class="booking-photo-info">
            <div class="booking-photo-id">الطلب #{{ $b->id }}</div>
            <div class="booking-photo-date">{{ $b->created_at->format('d/m/Y') }}</div>
        </div>
        <div class="booking-photo-count">{{ $b->photos->count() }} صورة</div>
        <i class="bi bi-chevron-left" style="color:#d1d5db;font-size:14px;"></i>
    </a>
@empty
    <div class="empty-photos">
        <div style="font-size:48px;margin-bottom:16px;opacity:.5">📷</div>
        <p class="font-bold text-gray-700">لا توجد صور بعد</p>
        <p class="mt-2 text-sm text-gray-500">سيتم إضافة صور مشاريعك من قِبل الفريق.</p>
    </div>
@endforelse

@endsection
