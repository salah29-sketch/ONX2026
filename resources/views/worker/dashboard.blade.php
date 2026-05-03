<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة العامل — ONX</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', sans-serif; min-height: 100vh; background: #0a0a0a; color: #fff; }
        .top { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1); background: rgba(0,0,0,.3); }
        .top h1 { font-size: 1.25rem; font-weight: 800; }
        .container { max-width: 900px; margin: 0 auto; padding: 1.5rem; }
        .filters { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem; align-items: center; }
        .filters a, .filters span { padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; text-decoration: none; background: rgba(255,255,255,.08); color: rgba(255,255,255,.8); border: 1px solid rgba(255,255,255,.1); }
        .filters a:hover { background: rgba(249,115,22,.2); color: #fb923c; border-color: rgba(249,115,22,.3); }
        .filters .active { background: rgba(249,115,22,.25); color: #fb923c; border-color: #f97316; }
        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; padding: 1.25rem; margin-bottom: 1rem; }
        .card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .card .meta { display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.85rem; color: rgba(255,255,255,.6); margin-bottom: 0.5rem; }
        .card .meta span { display: inline-flex; align-items: center; gap: 0.35rem; }
        .badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
        .badge-unconfirmed { background: rgba(156,163,175,.2); color: #d1d5db; }
        .badge-confirmed { background: rgba(59,130,246,.2); color: #93c5fd; }
        .badge-in_progress { background: rgba(249,115,22,.2); color: #fdba74; }
        .badge-completed { background: rgba(34,197,94,.2); color: #86efac; }
        .badge-cancelled { background: rgba(239,68,68,.2); color: #fca5a5; }
        .empty { text-align: center; padding: 3rem; color: rgba(255,255,255,.4); }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 0.5rem 0.75rem; border-radius: 8px; font-size: 0.875rem; text-decoration: none; background: rgba(255,255,255,.08); color: rgba(255,255,255,.8); }
        .pagination a:hover { background: rgba(249,115,22,.2); color: #fb923c; }
        .pagination .disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body>
    <header class="top">
        <h1>أعمال الفعاليات</h1>
        <form method="POST" action="{{ route('worker.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.7);cursor:pointer;font-size:0.9rem;">تسجيل الخروج</button>
        </form>
    </header>

    <div class="container">
        <div class="filters">
            <span>التاريخ:</span>
            <a href="{{ request()->fullUrlWithQuery(['date' => 'all', 'status' => request('status')]) }}" class="{{ ($dateFilter ?? 'all') === 'all' ? 'active' : '' }}">الكل</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => 'today', 'status' => request('status')]) }}" class="{{ ($dateFilter ?? '') === 'today' ? 'active' : '' }}">اليوم</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => 'upcoming', 'status' => request('status')]) }}" class="{{ ($dateFilter ?? '') === 'upcoming' ? 'active' : '' }}">القادمة</a>
            <span style="margin-inline-start:1rem;">الحالة:</span>
            <a href="{{ request()->fullUrlWithQuery(['date' => request('date'), 'status' => null]) }}" class="{{ empty($statusFilter) ? 'active' : '' }}">الكل</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => request('date'), 'status' => 'confirmed']) }}" class="{{ ($statusFilter ?? '') === 'confirmed' ? 'active' : '' }}">مؤكد</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => request('date'), 'status' => 'in_progress']) }}" class="{{ ($statusFilter ?? '') === 'in_progress' ? 'active' : '' }}">قيد التنفيذ</a>
            <a href="{{ request()->fullUrlWithQuery(['date' => request('date'), 'status' => 'completed']) }}" class="{{ ($statusFilter ?? '') === 'completed' ? 'active' : '' }}">مكتمل</a>
        </div>

        @forelse($bookings as $booking)
            @php
                $statusLabels = [
                    'unconfirmed' => 'غير مؤكد',
                    'confirmed'   => 'مؤكد',
                    'in_progress' => 'قيد التنفيذ',
                    'completed'   => 'مكتمل',
                    'cancelled'   => 'ملغى',
                ];
            @endphp
            <div class="card">
                <h3>
                    {{ $booking->package?->name ?? $booking->service?->name ?? '—' }}
                    <span class="badge badge-{{ $booking->status }}" style="margin-inline-start:.5rem;">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </h3>
                <div class="meta">
                    <span><i class="fas fa-user"></i> {{ $booking->client?->name ?? $booking->name ?? '—' }}</span>
                    <span><i class="fas fa-phone"></i> {{ $booking->phone ?? '—' }}</span>
                    @if($booking->event_date)
                        <span><i class="fas fa-calendar"></i> {{ $booking->event_date->translatedFormat('Y-m-d (l)') }}</span>
                    @endif
                    @if($booking->eventLocation)
                        <span><i class="fas fa-map-marker-alt"></i> {{ $booking->eventLocation->name }}</span>
                    @elseif($booking->custom_event_location)
                        <span><i class="fas fa-map-marker-alt"></i> {{ $booking->custom_event_location }}</span>
                    @endif
                </div>
                @if($booking->notes)
                    <p style="font-size:.85rem;color:rgba(255,255,255,.5);margin-top:.5rem;">{{ $booking->notes }}</p>
                @endif
            </div>
        @empty
            <div class="empty">لا توجد فعاليات.</div>
        @endforelse

        @if($bookings->hasPages())
            <div class="pagination">
                @if($bookings->onFirstPage())
                    <span class="disabled">السابق</span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}">السابق</a>
                @endif
                @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                    @if($page == $bookings->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}">التالي</a>
                @else
                    <span class="disabled">التالي</span>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
