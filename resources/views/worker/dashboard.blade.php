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
        .top h1 { font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: .5rem; }

        .container { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }
        .grid-2 { display: grid; grid-template-columns: 340px 1fr; gap: 1.25rem; }

        .alert-success { background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.3); color: #86efac; border-radius: 12px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .875rem; }

        .cal-card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; padding: 1.25rem; }
        .cal-nav { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .cal-nav button { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.8); border-radius: 8px; padding: .3rem .75rem; cursor: pointer; font-size: .85rem; }
        .cal-nav button:hover { background: rgba(249,115,22,.2); border-color: rgba(249,115,22,.4); color: #fb923c; }
        .cal-nav .month-title { font-size: 1rem; font-weight: 700; }

        .cal-days-names {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-size: .7rem;
            color: rgba(255,255,255,.4);
            margin-bottom: .4rem;
            direction: rtl !important;
        }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-template-rows: repeat(6, 38px);
            gap: 3px;
            direction: rtl !important;
        }

        .cal-day {
            text-align: center;
            border-radius: 8px;
            font-size: .8rem;
            cursor: pointer;
            color: rgba(255,255,255,.6);
            height: 38px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            background: rgba(255,255,255,.03);
            border: 1px solid transparent;
            transition: all .15s;
        }
        .cal-day:hover { background: rgba(249,115,22,.15); border-color: rgba(249,115,22,.3); }
        .cal-day.today { border-color: rgba(249,115,22,.6); color: #fb923c; font-weight: 700; }
        .cal-day.has-booking { background: rgba(249,115,22,.1); border-color: rgba(249,115,22,.35); }
        .cal-day.has-booking::after { content: ''; display: block; width: 5px; height: 5px; background: #f97316; border-radius: 50%; margin: 2px auto 0; }
        .cal-day.selected { background: rgba(249,115,22,.3); border-color: #f97316; color: #fb923c; font-weight: 700; }

        .cal-legend { display: flex; align-items: center; gap: .5rem; margin-top: .75rem; font-size: .75rem; color: rgba(255,255,255,.4); }
        .cal-legend-dot { width: 8px; height: 8px; background: #f97316; border-radius: 50%; }

        .add-btn { display: flex; align-items: center; gap: .5rem; background: rgba(249,115,22,.2); color: #f97316; border: 1px solid rgba(249,115,22,.4); border-radius: 10px; padding: .6rem 1rem; cursor: pointer; font-size: .875rem; font-weight: 700; font-family: 'Cairo', sans-serif; width: 100%; justify-content: center; margin-top: .75rem; transition: all .15s; }
        .add-btn:hover { background: rgba(249,115,22,.35); }

        .filters { display: flex; flex-wrap: wrap; gap: .6rem; margin-bottom: 1rem; align-items: center; }
        .filters a, .filters span { padding: .4rem .9rem; border-radius: 10px; font-size: .8rem; font-weight: 700; text-decoration: none; background: rgba(255,255,255,.08); color: rgba(255,255,255,.7); border: 1px solid rgba(255,255,255,.1); }
        .filters a:hover { background: rgba(249,115,22,.2); color: #fb923c; border-color: rgba(249,115,22,.3); }
        .filters .active { background: rgba(249,115,22,.25); color: #fb923c; border-color: #f97316; }
        .filter-label { background: transparent !important; border-color: transparent !important; color: rgba(255,255,255,.4) !important; font-size: .75rem !important; }

        .card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 14px; padding: 1rem 1.1rem; margin-bottom: .75rem; }
        .card h3 { font-size: .95rem; font-weight: 700; margin-bottom: .4rem; }
        .card .meta { display: flex; flex-wrap: wrap; gap: .75rem; font-size: .82rem; color: rgba(255,255,255,.55); }
        .card .meta span { display: inline-flex; align-items: center; gap: .3rem; }
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 8px; font-size: .72rem; font-weight: 700; }
        .badge-pending    { background: rgba(156,163,175,.2); color: #d1d5db; }
        .badge-confirmed  { background: rgba(59,130,246,.2);  color: #93c5fd; }
        .badge-in_progress{ background: rgba(249,115,22,.2);  color: #fdba74; }
        .badge-completed  { background: rgba(34,197,94,.2);   color: #86efac; }
        .badge-cancelled  { background: rgba(239,68,68,.2);   color: #fca5a5; }
        .empty-state { text-align: center; padding: 2.5rem; color: rgba(255,255,255,.3); }

        .pagination { display: flex; justify-content: center; gap: .5rem; margin-top: 1.25rem; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: .4rem .7rem; border-radius: 8px; font-size: .8rem; text-decoration: none; background: rgba(255,255,255,.08); color: rgba(255,255,255,.7); }
        .pagination a:hover { background: rgba(249,115,22,.2); color: #fb923c; }
        .pagination .disabled { opacity: .4; cursor: not-allowed; }

        .modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 200; align-items: center; justify-content: center; }
        .modal-bg.open { display: flex; }
        .modal { background: #111; border: 1px solid rgba(255,255,255,.12); border-radius: 18px; padding: 1.5rem; width: min(500px, 95vw); max-height: 90vh; overflow-y: auto; }
        .modal h2 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1.25rem; display: flex; align-items: center; gap: .5rem; }
        .field { margin-bottom: .9rem; }
        .field label { display: block; font-size: .8rem; color: rgba(255,255,255,.5); margin-bottom: .3rem; }
        .field input, .field select, .field textarea { width: 100%; padding: .55rem .85rem; border-radius: 10px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.06); color: #fff; font-size: .875rem; font-family: 'Cairo', sans-serif; }
        .field textarea { height: 70px; resize: none; }
        .field input::placeholder { color: rgba(255,255,255,.3); }
        .field select option { background: #1a1a1a; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
        .modal-actions { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.25rem; }
        .btn-cancel { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.7); border-radius: 10px; padding: .55rem 1.1rem; cursor: pointer; font-size: .875rem; font-family: 'Cairo', sans-serif; }
        .btn-save { background: #f97316; color: #fff; border: none; border-radius: 10px; padding: .55rem 1.4rem; cursor: pointer; font-size: .875rem; font-weight: 700; font-family: 'Cairo', sans-serif; }
        .btn-save:hover { background: #ea6c0a; }

        .selected-day-title { font-size: .85rem; color: rgba(255,255,255,.5); margin-bottom: .75rem; display: flex; align-items: center; justify-content: space-between; }
        .clear-sel { font-size: .75rem; color: #f97316; cursor: pointer; text-decoration: underline; }

        @media(max-width:700px){ .grid-2{ grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="top">
        <h1><i class="fas fa-heart" style="color:#f97316"></i> حجوزات حفلات الزفاف</h1>
        <form method="POST" action="{{ route('worker.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.6);cursor:pointer;font-size:.875rem;font-family:'Cairo',sans-serif;">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </button>
        </form>
    </header>

    <div class="container">

        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="grid-2">

            <div>
                <div class="cal-card">
                    <div class="cal-nav">
                        <button onclick="changeMonth(-1)">‹ السابق</button>
                        <span class="month-title" id="month-title"></span>
                        <button onclick="changeMonth(1)">التالي ›</button>
                    </div>
                    {{-- أسماء الأيام بالفرنسية: Sam أول عمود من اليمين --}}
                    <div class="cal-days-names">
                        <div>Dim</div>
                        <div>Lun</div>
                        <div>Mar</div>
                        <div>Mer</div>
                        <div>Jeu</div>
                        <div>Ven</div>
                        <div>Sam</div>
                    </div>
                    <div class="cal-grid" id="cal-grid"></div>
                    <div class="cal-legend"><span class="cal-legend-dot"></span> يوم فيه حجز</div>
                </div>
                <button class="add-btn" onclick="openModal()">
                    <i class="fas fa-plus"></i> إضافة حجز زفاف
                </button>
            </div>

            <div>
                <div class="filters">
                    <span class="filter-label">التاريخ:</span>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>'all','status'=>request('status')]) }}" class="{{ ($dateFilter??'all')==='all'?'active':'' }}">الكل</a>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>'today','status'=>request('status')]) }}" class="{{ ($dateFilter??'')==='today'?'active':'' }}">اليوم</a>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>'upcoming','status'=>request('status')]) }}" class="{{ ($dateFilter??'')==='upcoming'?'active':'' }}">القادمة</a>
                    <span class="filter-label" style="margin-inline-start:.5rem;">الحالة:</span>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>request('date'),'status'=>null]) }}" class="{{ empty($statusFilter)?'active':'' }}">الكل</a>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>request('date'),'status'=>'confirmed']) }}" class="{{ ($statusFilter??'')==='confirmed'?'active':'' }}">مؤكد</a>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>request('date'),'status'=>'in_progress']) }}" class="{{ ($statusFilter??'')==='in_progress'?'active':'' }}">قيد التنفيذ</a>
                    <a href="{{ request()->fullUrlWithQuery(['date'=>request('date'),'status'=>'completed']) }}" class="{{ ($statusFilter??'')==='completed'?'active':'' }}">مكتمل</a>
                </div>

                <div id="selected-day-title" class="selected-day-title" style="display:none">
                    <span id="selected-day-text"></span>
                    <span class="clear-sel" onclick="clearDayFilter()">عرض الكل</span>
                </div>

                <div id="bookings-container">
                    @forelse($bookings as $booking)
                        @php
                            $statusLabels = [
                                'pending'     => 'قيد المراجعة',
                                'confirmed'   => 'مؤكد',
                                'in_progress' => 'قيد التنفيذ',
                                'completed'   => 'مكتمل',
                                'cancelled'   => 'ملغى',
                            ];
                        @endphp
                        <div class="card" data-date="{{ $booking->event_date?->format('Y-m-d') }}">
                            <h3>
                                {{ $booking->package?->name ?? $booking->service?->name ?? '—' }}
                                <span class="badge badge-{{ $booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : $booking->status }}" style="margin-inline-start:.4rem;">
                                    {{ $statusLabels[$booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : $booking->status] ?? $booking->status }}
                                </span>
                            </h3>
                            <div class="meta">
                                <span><i class="fas fa-user"></i> {{ $booking->client?->name ?? $booking->name ?? '—' }}</span>
                                <span><i class="fas fa-phone"></i> {{ $booking->phone ?? '—' }}</span>
                                @if($booking->event_date)
                                    <span><i class="fas fa-calendar"></i> {{ $booking->event_date->format('Y-m-d') }}</span>
                                @endif
                                @if($booking->eventBooking?->venue_custom)
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $booking->eventBooking->venue_custom }}</span>
                                @endif
                                @if($booking->eventBooking?->start_time)
                                    <span><i class="fas fa-clock"></i> {{ $booking->eventBooking->start_time }}</span>
                                @endif
                            </div>
                            @if($booking->notes)
                                <p style="font-size:.8rem;color:rgba(255,255,255,.4);margin-top:.5rem;">{{ $booking->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state"><i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:.75rem;display:block;"></i> لا توجد حجوزات.</div>
                    @endforelse
                </div>

                @if($bookings->hasPages())
                    <div class="pagination">
                        @if($bookings->onFirstPage())
                            <span class="disabled">السابق</span>
                        @else
                            <a href="{{ $bookings->previousPageUrl() }}">السابق</a>
                        @endif
                        @foreach($bookings->getUrlRange(1,$bookings->lastPage()) as $page => $url)
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
        </div>
    </div>

    <div class="modal-bg" id="modal">
        <div class="modal">
            <h2><i class="fas fa-heart" style="color:#f97316"></i> إضافة حجز زفاف</h2>
            <form method="POST" action="{{ route('worker.bookings.store') }}">
                @csrf
                <div class="field">
                    <label>اسم العميل *</label>
                    <input type="text" name="name" required placeholder="الاسم الكامل" value="{{ old('name') }}">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>رقم الهاتف *</label>
                        <input type="tel" name="phone" required placeholder="05xxxxxxxx" value="{{ old('phone') }}">
                    </div>
                    <div class="field">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" placeholder="email@example.com" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>تاريخ الحفل *</label>
                        <input type="date" name="event_date" required id="modal-date" value="{{ old('event_date') }}">
                    </div>
                    <div class="field">
                        <label>الباقة</label>
                        <select name="package_id">
                            <option value="">بدون باقة</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ old('package_id')==$pkg->id?'selected':'' }}>{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>وقت البداية</label>
                        <input type="time" name="start_time" value="{{ old('start_time') }}">
                    </div>
                    <div class="field">
                        <label>وقت النهاية</label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}">
                    </div>
                </div>
                <div class="field">
                    <label>المكان (قاعة / فندق)</label>
                    <input type="text" name="venue_custom" placeholder="مثال: قاعة النجوم، وهران" value="{{ old('venue_custom') }}">
                </div>
                <div class="field">
                    <label>ملاحظات</label>
                    <textarea name="notes" placeholder="أي تفاصيل إضافية...">{{ old('notes') }}</textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">إلغاء</button>
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ الحجز</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // بيانات الحجوزات — تتحدث عند تغيير الشهر بدون إعادة تحميل
        let calBookings = @json($calBookingsJson);

        const MONTHS_FR = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

        let curDate = new Date();
        curDate.setFullYear({{ $calYear }});
        curDate.setMonth({{ $calMonth - 1 }});
        let selectedDay = null;

        function renderCalendar() {
            const y = curDate.getFullYear();
            const m = curDate.getMonth();
            document.getElementById('month-title').textContent = MONTHS_FR[m] + ' ' + y;

            const bookedDates = new Set(calBookings.map(b => b.date));
            const today = new Date();
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const jsDay = new Date(y, m, 1).getDay();

            // Tailwind يجعل الـ grid LTR: col1=يسار=Dim, col7=يمين=Sam
            const COL_MAP = { 0:1, 1:2, 2:3, 3:4, 4:5, 5:6, 6:7 };
            const startCol = COL_MAP[jsDay];

            let html = '';
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = y + '-' + String(m + 1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
                let cls = 'cal-day';
                if (today.getFullYear()===y && today.getMonth()===m && today.getDate()===d) cls += ' today';
                if (bookedDates.has(dateStr)) cls += ' has-booking';
                if (selectedDay === d) cls += ' selected';
                const style = (d === 1) ? ` style="grid-column:${startCol}"` : '';
                html += `<div class="${cls}"${style} onclick="selectDay(${d},'${dateStr}')">${d}</div>`;
            }
            document.getElementById('cal-grid').innerHTML = html;
        }

        function selectDay(d, dateStr) {
            selectedDay = d;
            renderCalendar();
            const cards = document.querySelectorAll('#bookings-container .card');
            let found = 0;
            cards.forEach(card => {
                if (card.dataset.date === dateStr) { card.style.display = ''; found++; }
                else card.style.display = 'none';
            });
            document.getElementById('selected-day-text').textContent =
                'حجوزات ' + d + ' ' + MONTHS_FR[curDate.getMonth()] + ' (' + found + ')';
            document.getElementById('selected-day-title').style.display = 'flex';
            document.getElementById('modal-date').value = dateStr;
        }

        function clearDayFilter() {
            selectedDay = null;
            renderCalendar();
            document.querySelectorAll('#bookings-container .card').forEach(c => c.style.display = '');
            document.getElementById('selected-day-title').style.display = 'none';
        }

        async function changeMonth(dir) {
            curDate.setMonth(curDate.getMonth() + dir);
            selectedDay = null;
            document.getElementById('selected-day-title').style.display = 'none';

            const y = curDate.getFullYear();
            const m = String(curDate.getMonth() + 1).padStart(2,'0');

            // تحديث الـ URL بدون إعادة تحميل
            history.pushState({}, '', '?month=' + y + '-' + m);

            // جلب بيانات الحجوزات للشهر الجديد
            try {
                const res = await fetch('{{ route("worker.dashboard") }}?month=' + y + '-' + m, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    calBookings = data.calBookings ?? [];

                    // تحديث قائمة الحجوزات
                    if (data.bookingsHtml !== undefined) {
                        document.getElementById('bookings-container').innerHTML = data.bookingsHtml;
                    }
                }
            } catch(e) {}

            renderCalendar();
        }

        function openModal()  { document.getElementById('modal').classList.add('open'); }
        function closeModal() { document.getElementById('modal').classList.remove('open'); }

        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        renderCalendar();

        @if($errors->any())
            openModal();
        @endif
    </script>
</body>
</html>