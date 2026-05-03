@extends('layouts.admin')

@section('styles')
<style>
    .bk-wrap{
        display:grid;
        grid-template-columns:minmax(0, 1fr) 320px;
        gap:18px;
        align-items:start;
    }

    .bk-panel{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:22px;
        padding:18px;
        box-shadow:0 10px 30px rgba(15,23,42,.05);
    }

    .bk-stats{
        display:grid;
        grid-template-columns:repeat(4, minmax(0,1fr));
        gap:14px;
        margin-bottom:18px;
    }

    .bk-stat{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:16px;
        box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .bk-stat-label{
        font-size:13px;
        color:#64748b;
        font-weight:700;
        margin-bottom:8px;
    }

    .bk-stat-value{
        font-size:28px;
        font-weight:900;
        color:#0f172a;
        line-height:1;
    }

    .bk-calendar-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:14px;
    }

    .bk-calendar-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#0f172a;
    }

    .bk-calendar-subtitle{
        margin:5px 0 0;
        font-size:13px;
        color:#64748b;
    }

    .bk-legend{
        display:flex;
        align-items:center;
        gap:8px;
        flex-wrap:wrap;
        margin-top:14px;
    }

    .bk-pill{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:7px 11px;
        border-radius:999px;
        background:#f8fafc;
        border:1px solid #e2e8f0;
        color:#334155;
        font-size:12px;
        font-weight:700;
    }

    .bk-dot{
        width:9px;
        height:9px;
        border-radius:999px;
        display:inline-block;
    }

    .dot-unconfirmed{ background:#f59e0b; }
    .dot-confirmed{ background:#22c55e; }
    .dot-progress{ background:#3b82f6; }
    .dot-completed{ background:#0f172a; }
    .dot-cancelled{ background:#ef4444; }

    /* حجم ثابت للتقويم — إخفاء شريط التمرير واستيعاب 6 أسابيع */
    #bookingsCalendar{
        min-height: 0;
    }

    #bookingsCalendar .fc-scroller-liquid{
        overflow: hidden !important;
    }

    #bookingsCalendar .fc-scroller{
        overflow: hidden !important;
    }

    #bookingsCalendar .fc{
        font-family: inherit;
    }

    #bookingsCalendar .fc-toolbar{
        margin-bottom:14px !important;
    }

    #bookingsCalendar .fc-toolbar-title{
        font-size:24px !important;
        font-weight:900 !important;
        color:#0f172a !important;
    }

   

    #bookingsCalendar .fc-button:hover,
    #bookingsCalendar .fc-button-active{
        background:#f97316 !important;
    }

    #bookingsCalendar .fc-col-header-cell{
        background:#f8fafc;
    }

    #bookingsCalendar .fc-col-header-cell-cushion{
        color:#0f172a !important;
        font-size:13px;
        font-weight:800;
        text-decoration:none !important;
        padding:10px 4px !important;
    }

    #bookingsCalendar .fc-daygrid-day{
        background:#fff;
        transition:.15s ease;
        cursor:pointer;
    }

    #bookingsCalendar .fc-daygrid-day:hover{
        background:#fffaf5;
    }

    #bookingsCalendar .fc-daygrid-day-number{
        color:#0f172a !important;
        font-size:13px;
        font-weight:800;
        text-decoration:none !important;
        padding:8px !important;
    }

    #bookingsCalendar .fc-day-today{
        background:rgba(249,115,22,.10) !important;
    }

    #bookingsCalendar .fc-daygrid-day.selected-day{
        box-shadow: inset 0 0 0 2px #f97316;
        background:#fff7ed !important;
    }

    /* تلوين كامل مساحة اليوم عند وجود حجز */
    #bookingsCalendar .fc-daygrid-day.bk-day-has-booking{
        background: rgba(249,115,22,.12) !important;
    }

    #bookingsCalendar .fc-daygrid-day.bk-day-has-booking:hover{
        background: rgba(249,115,22,.18) !important;
    }

    #bookingsCalendar .fc-daygrid-day.bk-day-has-booking.fc-day-today{
        background: rgba(249,115,22,.22) !important;
    }

    #bookingsCalendar .fc-daygrid-day.bk-day-has-booking.selected-day{
        background: #fff7ed !important;
        box-shadow: inset 0 0 0 2px #f97316;
    }

    #bookingsCalendar .fc-daygrid-day-frame{
        min-height: 64px;
    }

    #bookingsCalendar .fc-event{
        border:none !important;
        border-radius:8px !important;
        font-size:11px !important;
        font-weight:700 !important;
        padding:2px 6px !important;
        box-shadow:0 6px 14px rgba(15,23,42,.06);
    }

    #bookingsCalendar .fc-event,
    #bookingsCalendar .fc-event:hover{
        text-decoration:none !important;
    }

    .bk-status-unconfirmed{
        background:rgba(245,158,11,.18) !important;
        color:#92400e !important;
    }

    .bk-status-confirmed{
        background:rgba(34,197,94,.18) !important;
        color:#166534 !important;
    }

    .bk-status-progress{
        background:rgba(59,130,246,.18) !important;
        color:#1d4ed8 !important;
    }

    .bk-status-completed{
        background:rgba(15,23,42,.12) !important;
        color:#0f172a !important;
    }

    .bk-status-cancelled{
        background:rgba(239,68,68,.18) !important;
        color:#991b1b !important;
    }

    .bk-side-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#0f172a;
    }

    .bk-side-subtitle{
        margin:5px 0 14px;
        font-size:13px;
        color:#64748b;
    }

    .bk-day-list{
        display:grid;
        gap:10px;
    }

    .bk-empty{
        border:1px dashed #cbd5e1;
        border-radius:16px;
        padding:16px;
        background:#f8fafc;
        color:#64748b;
        text-align:center;
        font-size:14px;
        line-height:1.8;
    }

    .bk-card{
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:12px;
        background:#fff;
    }

    .bk-card-title{
        font-size:14px;
        font-weight:800;
        color:#0f172a;
        margin-bottom:6px;
    }

    .bk-card-meta{
        font-size:13px;
        color:#475569;
        line-height:1.8;
        margin-bottom:10px;
    }

    .bk-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:5px 10px;
        border-radius:999px;
        font-size:11px;
        font-weight:800;
        margin-bottom:10px;
    }

    .bk-badge.unconfirmed{
        background:rgba(245,158,11,.16);
        color:#92400e;
    }

    .bk-badge.confirmed{
        background:rgba(34,197,94,.16);
        color:#166534;
    }

    .bk-badge.in_progress{
        background:rgba(59,130,246,.16);
        color:#1d4ed8;
    }

    .bk-badge.completed{
        background:rgba(15,23,42,.10);
        color:#0f172a;
    }

    .bk-badge.cancelled{
        background:rgba(239,68,68,.16);
        color:#991b1b;
    }

    .bk-view-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        padding:8px 12px;
        border-radius:12px;
        background:#f97316;
        color:#fff !important;
        text-decoration:none !important;
        font-size:12px;
        font-weight:800;
    }

    .bk-view-btn:hover{
        background:#ea580c;
        color:#fff !important;
    }

    @media (max-width: 992px){
        .bk-stats{
            grid-template-columns:repeat(2, minmax(0,1fr));
        }

        .bk-wrap{
            grid-template-columns:1fr;
        }
    }

    @media (max-width: 768px){
        .bk-stats{
            grid-template-columns:1fr 1fr;
            gap:10px;
        }

        .bk-stat{
            padding:14px;
        }

        .bk-stat-value{
            font-size:22px;
        }

        #bookingsCalendar .fc-toolbar-title{
            font-size:19px !important;
        }

        #bookingsCalendar .fc-daygrid-day-frame{
            min-height:74px;
        }

        #bookingsCalendar .fc-event{
    border-radius:10px !important;
    padding:3px 6px !important;
    font-size:11px !important;
    text-align:center;
    white-space:nowrap;
}
    }
</style>
@endsection

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تقويم مراقبة الحجوزات</h1>
        <div class="db-page-subtitle">مراقبة الأيام المحجوزة للحفلات والانتقال مباشرة إلى تفاصيل الحجز.</div>
    </div>

    <a href="{{ route('admin.bookings.index') }}" class="db-btn-secondary">
        <i class="fas fa-list"></i>
        الرجوع إلى الحجوزات
    </a>
</div>

<div class="bk-stats">
    <div class="bk-stat">
        <div class="bk-stat-label">إجمالي الحجوزات</div>
        <div class="bk-stat-value" id="statTotal">{{ $stats['total'] ?? 0 }}</div>
    </div>

    <div class="bk-stat">
        <div class="bk-stat-label">غير مؤكدة</div>
        <div class="bk-stat-value" id="statUnconfirmed">{{ $stats['unconfirmed'] ?? 0 }}</div>
    </div>

    <div class="bk-stat">
        <div class="bk-stat-label">مؤكدة</div>
        <div class="bk-stat-value" id="statConfirmed">{{ $stats['confirmed'] ?? 0 }}</div>
    </div>

    <div class="bk-stat">
        <div class="bk-stat-label">ملغاة</div>
        <div class="bk-stat-value" id="statCancelled">{{ $stats['cancelled'] ?? 0 }}</div>
    </div>
</div>

<div class="bk-wrap">
    <div class="db-card bk-panel">
        <div class="bk-calendar-head">
            <div>
                <h2 class="bk-calendar-title">تقويم الشهر</h2>
                <div class="bk-calendar-subtitle">اضغط على أي يوم لعرض الحجوزات الخاصة به.</div>
            </div>
        </div>

        <div id="bookingsCalendar"></div>

        <div class="bk-legend">
            <span class="bk-pill"><span class="bk-dot dot-unconfirmed"></span> غير مؤكد</span>
            <span class="bk-pill"><span class="bk-dot dot-confirmed"></span> مؤكد</span>
            <span class="bk-pill"><span class="bk-dot dot-progress"></span> قيد التنفيذ</span>
            <span class="bk-pill"><span class="bk-dot dot-completed"></span> مكتمل</span>
            <span class="bk-pill"><span class="bk-dot dot-cancelled"></span> ملغى</span>
        </div>
    </div>

    <div class="db-card bk-panel">
        <div class="db-card-header py-3">
            <span class="text-white font-weight-bold" id="selectedDayTitle">اختر يومًا</span>
        </div>
        <div class="db-card-body">
        <div class="bk-side-subtitle mb-2" id="selectedDaySubtitle">ستظهر هنا الحجوزات الخاصة بهذا اليوم.</div>
        <div class="bk-day-list" id="selectedDayList">
            <div class="bk-empty">اختر يومًا من التقويم لعرض الحجوزات الموجودة فيه.</div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('bookingsCalendar');
    const rawItems = @json($calendarItems);

    function normalizeStatus(status) {
        const s = (status || 'unconfirmed').toString();
        if (['confirmed', 'in_progress', 'completed', 'cancelled'].includes(s)) return s;
        return 'unconfirmed';
    }

    function statusLabel(status) {
        switch (status) {
            case 'confirmed': return 'مؤكد';
            case 'in_progress': return 'قيد التنفيذ';
            case 'completed': return 'مكتمل';
            case 'cancelled': return 'ملغى';
            default: return 'غير مؤكد';
        }
    }

    function fcClass(status) {
        switch (status) {
            case 'confirmed': return 'bk-status-confirmed';
            case 'in_progress': return 'bk-status-progress';
            case 'completed': return 'bk-status-completed';
            case 'cancelled': return 'bk-status-cancelled';
            default: return 'bk-status-unconfirmed';
        }
    }

    function renderDayDetails(dateStr) {
        const dayItems = rawItems.filter(item => item.start === dateStr);

        document.getElementById('selectedDayTitle').textContent = dateStr;
        document.getElementById('selectedDaySubtitle').textContent = `عدد الحجوزات في هذا اليوم: ${dayItems.length}`;

        const list = document.getElementById('selectedDayList');

        if (!dayItems.length) {
            list.innerHTML = `<div class="bk-empty">لا توجد حجوزات في هذا اليوم.</div>`;
            return;
        }

        list.innerHTML = dayItems.map(item => {
            const status = normalizeStatus(item.status);
            const service = item.service_name || (item.needs_calendar === false ? 'إعلانات' : 'حفلات');
            const location = item.location_name || item.location || '—';
            const url = item.url || '#';
            const title = (item.title || 'حجز').replace(/</g, '&lt;').replace(/>/g, '&gt;');

            return `
                <div class="bk-card">
                    <div class="bk-badge ${status}">${statusLabel(status)}</div>
                    <div class="bk-card-title">${title}</div>
                    <div class="bk-card-meta">
                        <div><strong>الخدمة:</strong> ${service}</div>
                        <div><strong>المكان:</strong> ${location}</div>
                    </div>
                    <a href="${url}" class="bk-view-btn">عرض الحجز</a>
                </div>
            `;
        }).join('');
    }

    const events = rawItems.map(item => ({
        ...item,
        title: (item.title || (item.needs_calendar === false ? 'Pub' : 'حفل')),
        classNames: [fcClass(normalizeStatus(item.status))]
    }));

    const datesWithEvents = new Set(rawItems.map(item => item.start));

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        direction: 'ltr',
        height: 490,
        contentHeight: 430,
        fixedWeekCount: true,
        headerToolbar: {
            start: 'title',
            center: '',
            end: 'today prev,next'
        },
        dayMaxEvents: 2,
        events: events,
        dayCellDidMount: function(info) {
            const dateStr = info.date.getFullYear() + '-' +
                String(info.date.getMonth() + 1).padStart(2, '0') + '-' +
                String(info.date.getDate()).padStart(2, '0');
            if (datesWithEvents.has(dateStr)) {
                info.el.classList.add('bk-day-has-booking');
            }
        },
        dateClick: function(info) {
            document.querySelectorAll('#bookingsCalendar .fc-daygrid-day').forEach(el => {
                el.classList.remove('selected-day');
            });

            const cell = document.querySelector(`#bookingsCalendar .fc-daygrid-day[data-date="${info.dateStr}"]`);
            if (cell) cell.classList.add('selected-day');

            renderDayDetails(info.dateStr);
        },
        eventClick: function(info) {
            if (info.event.startStr) {
                document.querySelectorAll('#bookingsCalendar .fc-daygrid-day').forEach(el => {
                    el.classList.remove('selected-day');
                });

                const cell = document.querySelector(`#bookingsCalendar .fc-daygrid-day[data-date="${info.event.startStr}"]`);
                if (cell) cell.classList.add('selected-day');

                renderDayDetails(info.event.startStr);
            }

            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        }
    });

    calendar.render();
});
</script>
@endsection