<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تتبع حالة الحجز — ONX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body { min-height: 100%; font-family: 'Cairo', sans-serif; background: #060606; color: #fff; -webkit-font-smoothing: antialiased; }

        /*
         * LAYOUT — معكوس عن login:
         * login:  [vis-panel LEFT]  [form-panel RIGHT]
         * status: [form-panel LEFT] [vis-panel RIGHT]
         */
        .page { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }
        .form-panel { order: 1; }
        .vis-panel  { order: 2; }

        @media (max-width: 1023px) {
            .page { grid-template-columns: 1fr; }
            .vis-panel { display: none; }
        }

        /* ══ VISUAL PANEL ══ */
        .vis-panel { position: relative; background: #080808; overflow: hidden; display: flex; flex-direction: column; }
        .vis-glow-top    { position:absolute; top:-80px; right:-80px; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle,rgba(249,115,22,.18) 0%,transparent 70%); pointer-events:none; }
        .vis-glow-bottom { position:absolute; bottom:-100px; left:-60px; width:380px; height:380px; border-radius:50%; background:radial-gradient(circle,rgba(234,88,12,.1) 0%,transparent 70%); pointer-events:none; }
        .vis-grid { position:absolute; inset:0; background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px); background-size:40px 40px; }
        .vis-inner { position:relative; z-index:1; display:flex; flex-direction:column; height:100%; padding:2.5rem; }
        .vis-logo { display:flex; align-items:center; gap:8px; text-decoration:none; }
        .vis-logo-text { font-size:20px; font-weight:900; letter-spacing:.18em; color:#fff; }
        .vis-logo-dot  { width:9px; height:9px; border-radius:50%; background:#f97316; box-shadow:0 0 18px rgba(249,115,22,.85); }

        .preview-wrap { flex:1; display:flex; flex-direction:column; justify-content:center; padding:1.5rem 0; }
        .preview-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); border-radius:20px; padding:1.5rem; margin-bottom:2rem; }
        .pc-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.2rem; }
        .pc-head-title { font-size:13px; font-weight:800; color:rgba(255,255,255,.75); }
        .pc-badge { font-size:10px; font-weight:700; color:#86efac; background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.2); padding:3px 10px; border-radius:99px; display:flex; align-items:center; gap:4px; }
        .pc-badge::before { content:''; width:6px; height:6px; border-radius:50%; background:#22c55e; animation:livepulse 1.8s ease-in-out infinite; display:block; }
        @keyframes livepulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }

        .pc-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:1.2rem; }
        .pc-stat { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.07); border-radius:12px; padding:10px; text-align:center; }
        .pc-stat-val { font-size:22px; font-weight:900; color:#fff; line-height:1; }
        .pc-stat-lbl { font-size:10px; color:rgba(255,255,255,.35); margin-top:3px; }

        .pc-bars { display:flex; flex-direction:column; gap:8px; }
        .pc-bar-item { display:flex; align-items:center; gap:10px; }
        .pc-bar-lbl { font-size:11px; color:rgba(255,255,255,.4); width:50px; text-align:right; flex-shrink:0; }
        .pc-bar-track { flex:1; height:5px; background:rgba(255,255,255,.07); border-radius:99px; overflow:hidden; }
        .pc-bar-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg,#f97316,#fb923c); animation:bargrow .8s cubic-bezier(.4,0,.2,1) forwards; }
        @keyframes bargrow { from { width:0 !important; } }

        .ben-label { font-size:10px; font-weight:700; letter-spacing:.1em; color:rgba(255,255,255,.25); margin-bottom:.9rem; }
        .ben-list { list-style:none; display:flex; flex-direction:column; gap:.6rem; }
        .ben-item { display:flex; align-items:flex-start; gap:.75rem; }
        .ben-icon { width:32px; height:32px; border-radius:10px; background:rgba(249,115,22,.1); border:1px solid rgba(249,115,22,.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#fb923c; font-size:13px; }
        .ben-body strong { display:block; font-size:12px; font-weight:800; color:rgba(255,255,255,.8); margin-bottom:1px; }
        .ben-body span   { font-size:11px; color:rgba(255,255,255,.38); line-height:1.45; }

        .vis-bottom { display:flex; align-items:center; gap:.5rem; margin-top:2rem; }
        .vis-bottom-line { flex:1; height:1px; background:rgba(255,255,255,.07); }
        .vis-bottom-text { font-size:11px; color:rgba(255,255,255,.18); white-space:nowrap; }

        /* ══ FORM PANEL ══ */
        .form-panel { display:flex; flex-direction:column; justify-content:flex-start; align-items:center; background:#060606; padding:5rem 2rem 4rem; position:relative; overflow-y:auto; }
        .form-panel::before { content:''; position:absolute; top:-60px; right:-60px; width:320px; height:320px; border-radius:50%; background:radial-gradient(circle,rgba(249,115,22,.07) 0%,transparent 70%); pointer-events:none; }
        .form-inner { width:100%; max-width:440px; position:relative; z-index:1; }

        .switch-link { position:absolute; top:1.5rem; left:1.5rem; display:flex; align-items:center; gap:.4rem; font-size:12px; font-weight:700; color:rgba(255,255,255,.3); text-decoration:none; transition:color .15s; z-index:2; }
        .switch-link:hover { color:rgba(255,255,255,.65); }

        .welcome { margin-bottom:1.75rem; }
        .welcome h1 { font-size:26px; font-weight:900; color:#fff; margin-bottom:.4rem; line-height:1.2; }
        .welcome p  { font-size:13px; color:rgba(255,255,255,.42); line-height:1.6; }

        .search-card { background:rgba(255,255,255,.035); border:1px solid rgba(255,255,255,.08); border-radius:24px; padding:1.75rem; margin-bottom:1.5rem; }
        .card-eyebrow { font-size:10px; font-weight:700; letter-spacing:.1em; color:rgba(255,255,255,.28); margin-bottom:1.25rem; }

        .field { margin-bottom:1rem; }
        .field label { display:block; font-size:12.5px; font-weight:700; color:rgba(255,255,255,.72); margin-bottom:.45rem; }
        .field-input { width:100%; background:rgba(255,255,255,.04); border:1.5px solid rgba(255,255,255,.1); border-radius:14px; padding:.75rem 1rem; color:#fff; font-size:13.5px; font-family:'Cairo',sans-serif; outline:none; transition:border-color .2s,background .2s,box-shadow .2s; }
        .field-input::placeholder { color:rgba(255,255,255,.22); }
        .field-input:focus { border-color:rgba(249,115,22,.55); background:rgba(249,115,22,.03); box-shadow:0 0 0 4px rgba(249,115,22,.09); }
        .field-error { margin-top:.35rem; font-size:11.5px; color:#f87171; }

        .btn-primary { width:100%; padding:.875rem; border-radius:100px; border:none; background:#f97316; color:#000; font-size:15px; font-weight:900; font-family:'Cairo',sans-serif; cursor:pointer; box-shadow:0 4px 24px rgba(249,115,22,.26); transition:background .2s,transform .1s,box-shadow .2s; display:flex; align-items:center; justify-content:center; gap:.45rem; }
        .btn-primary:hover  { background:#fb923c; box-shadow:0 6px 30px rgba(249,115,22,.36); }
        .btn-primary:active { transform:scale(.98); }

        /* ══ BOOKING CARDS ══ */
        .results-wrap { display:flex; flex-direction:column; gap:14px; }

        .booking-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.09); border-radius:20px; padding:20px 22px; transition:border-color .2s; }
        .booking-card:hover { border-color:rgba(249,115,22,.25); }

        .bc-head { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px; flex-wrap:wrap; gap:8px; }
        .bc-meta  { font-size:11px; color:rgba(255,255,255,.4); margin-bottom:3px; }
        .bc-title { font-size:14px; font-weight:800; color:#fff; }

        .status-badge { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; }
        .status-badge .dot { width:7px; height:7px; border-radius:50%; }
        .status-unconfirmed,.status-new { background:rgba(250,204,21,.12); color:#fcd34d; border:1px solid rgba(250,204,21,.2); }
        .status-unconfirmed .dot,.status-new .dot { background:#facc15; }
        .status-confirmed   { background:rgba(52,211,153,.12); color:#6ee7b7; border:1px solid rgba(52,211,153,.2); }
        .status-confirmed   .dot { background:#34d399; }
        .status-in_progress { background:rgba(96,165,250,.12); color:#93c5fd; border:1px solid rgba(96,165,250,.2); }
        .status-in_progress .dot { background:#60a5fa; }
        .status-completed   { background:rgba(167,139,250,.12); color:#c4b5fd; border:1px solid rgba(167,139,250,.2); }
        .status-completed   .dot { background:#a78bfa; }
        .status-cancelled   { background:rgba(248,113,113,.12); color:#fca5a5; border:1px solid rgba(248,113,113,.2); }
        .status-cancelled   .dot { background:#f87171; }

        /* Timeline */
        .timeline { display:flex; align-items:flex-start; margin:0 0 6px; direction:rtl; }
        .t-step { flex:1; display:flex; flex-direction:column; align-items:center; gap:7px; position:relative; }
        .t-step:not(:last-child)::after { content:''; position:absolute; top:17px; left:calc(-50% + 20px); width:calc(100% - 40px); height:2px; background:rgba(255,255,255,.08); z-index:0; }
        .t-step.t-done:not(:last-child)::after,
        .t-step.t-active:not(:last-child)::after { background:rgba(34,197,94,.6); }
        .t-circle { width:36px; height:36px; border-radius:50%; border:2px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:rgba(255,255,255,.25); position:relative; z-index:2; }
        .t-step.t-active .t-circle { border-color:#f97316; background:rgba(249,115,22,.15); color:#f97316; box-shadow:0 0 0 4px rgba(249,115,22,.10); }
        .t-step.t-done   .t-circle { border-color:rgba(34,197,94,.5); background:rgba(34,197,94,.1); color:rgba(34,197,94,.85); }
        .t-label { font-size:10px; font-weight:700; color:rgba(255,255,255,.22); text-align:center; white-space:nowrap; }
        .t-step.t-active .t-label { color:#f97316; }
        .t-step.t-done   .t-label { color:rgba(34,197,94,.7); }
        .t-dot { width:5px; height:5px; border-radius:50%; background:#f97316; opacity:0; }
        .t-step.t-active .t-dot { animation:pulsedot 1.6s ease-in-out infinite; }
        @keyframes pulsedot { 0%,100%{opacity:.8;transform:scale(1)} 50%{opacity:.3;transform:scale(1.5)} }

        .hint-text { text-align:center; font-size:11px; color:rgba(255,255,255,.35); margin-bottom:14px; }
        .warn-text { text-align:center; font-size:11px; color:rgba(250,204,21,.7); margin-bottom:14px; }

        .pay-wrap { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.07); border-radius:14px; padding:14px 16px; }
        .pay-row  { display:flex; justify-content:space-between; margin-bottom:8px; font-size:12px; }
        .pay-label{ color:rgba(255,255,255,.45); font-weight:600; }
        .pay-val  { color:rgba(255,255,255,.7); font-weight:800; }
        .bar-bg   { background:rgba(255,255,255,.07); border-radius:99px; height:6px; overflow:hidden; }
        .bar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#f97316,#fb923c); }
        .pay-foot { display:flex; justify-content:space-between; margin-top:7px; font-size:11px; }
        .pay-paid { color:rgba(255,255,255,.3); }
        .pay-rest { color:#f87171; }
        .pay-done { color:rgba(34,197,94,.8); }

        .bc-cta { display:flex; align-items:center; justify-content:space-between; margin-top:14px; flex-wrap:wrap; gap:8px; }
        .bc-cta-text { font-size:11px; color:rgba(255,255,255,.3); }
        .bc-cta-btn { display:inline-flex; align-items:center; gap:5px; padding:7px 16px; border-radius:99px; border:1px solid rgba(249,115,22,.3); background:rgba(249,115,22,.08); color:#fb923c; font-size:11px; font-weight:800; text-decoration:none; transition:background .2s; font-family:'Cairo',sans-serif; }
        .bc-cta-btn:hover { background:rgba(249,115,22,.15); }

        .empty-state { text-align:center; padding:2.5rem 1rem; background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); border-radius:20px; margin-top:1rem; }
        .empty-state a { color:#fb923c; font-weight:700; text-decoration:none; }

        .login-hint { margin-top:1.25rem; text-align:center; font-size:12.5px; color:rgba(255,255,255,.35); }
        .login-hint a { color:#fb923c; font-weight:700; text-decoration:none; }
        .login-hint a:hover { color:#fdba74; }

        /* Transition — يتحرك من اليمين (عكس login الذي يتحرك من اليسار) */
        .page { animation:pageIn .4s cubic-bezier(.4,0,.2,1); }
        @keyframes pageIn { from{opacity:0;transform:translateX(18px)} to{opacity:1;transform:translateX(0)} }

        .error-alert { margin-bottom:1.25rem; padding:.875rem 1rem; border-radius:14px; border:1px solid rgba(239,68,68,.28); background:rgba(239,68,68,.07); font-size:13px; color:#fca5a5; font-weight:700; }

        .fade-up { opacity:0; transform:translateY(14px); animation:fadeUp .5s ease forwards; }
        .fu-d1{animation-delay:.06s} .fu-d2{animation-delay:.14s} .fu-d3{animation-delay:.22s}
        @keyframes fadeUp { to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body>
<div class="page">

    {{-- ══ FORM PANEL — يسار (order:1 في RTL = يمين فعلياً) ══ --}}
    <div class="form-panel">

        <a href="{{ route('client.login') }}" class="switch-link">
            <i class="bi bi-arrow-right"></i>
            تسجيل الدخول
        </a>

        <div class="form-inner">
            <div class="welcome fade-up fu-d1">
                <h1>تتبع حجزك 🔍</h1>
                <p>أدخل رقم هاتفك لعرض حالة حجوزاتك ومراحل المشروع.</p>
            </div>

            <div class="search-card fade-up fu-d2">
                <p class="card-eyebrow">بحث عن الحجز</p>
                <form method="POST" action="{{ route('booking.status.search') }}">
                    @csrf
                    <div class="field">
                        <label for="phone-field">رقم الهاتف</label>
                        <input type="tel" id="phone-field" name="phone"
                               value="{{ old('phone', $phone ?? '') }}"
                               placeholder="05xxxxxxxx" dir="ltr"
                               class="field-input @error('phone') has-error @enderror"
                               required>
                        @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-search"></i>
                        <span>بحث</span>
                    </button>
                </form>
            </div>

            @isset($bookings)
                @if($bookings->isNotEmpty())
                    <div class="results-wrap fade-up fu-d3">
                        @foreach($bookings as $booking)
                            @php
                                $isCancelled = $booking->status === 'cancelled';
                                $step        = $isCancelled ? 0 : $booking->statusStep();
                                $paid        = $booking->payments->sum('amount');
                                $total       = $booking->total_price ?? 0;
                                $remaining   = max(0, $total - $paid);
                                $payPercent  = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                                $isFullyPaid = $total > 0 && $remaining <= 0;
                                if ($step === 4 && !$isFullyPaid) $step = 3;
                                $timelineSteps = [
                                    1 => ['label'=>'استلام الطلب','icon'=>'📩'],
                                    2 => ['label'=>'تأكيد الحجز', 'icon'=>'✅'],
                                    3 => ['label'=>'قيد التنفيذ', 'icon'=>'🎬'],
                                    4 => ['label'=>'تم التسليم',  'icon'=>'🏁'],
                                ];
                            @endphp
                            <div class="booking-card">
                                <div class="bc-head">
                                    <div>
                                        <p class="bc-meta">حجز رقم <strong style="color:rgba(255,255,255,.65)">#{{ $booking->id }}</strong> &nbsp;·&nbsp; {{ $booking->created_at->format('d/m/Y') }}</p>
                                        <p class="bc-title">
                                            {{ $booking->booking_type === 'event' ? '🎪 تصوير فعاليات' : '📢 إعلانات' }}
                                            @if($booking->event_date)
                                                <span style="color:rgba(255,255,255,.35);font-size:12px;font-weight:400">· {{ \Carbon\Carbon::parse($booking->event_date)->format('d/m/Y') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="status-badge status-{{ $booking->status }}">
                                        <span class="dot"></span>{{ $booking->statusLabel() }}
                                    </span>
                                </div>

                                @if(!$isCancelled)
                                    <div class="timeline">
                                        @foreach($timelineSteps as $s => $info)
                                            @php $cls = $step > $s ? 't-done' : ($step === $s ? 't-active' : ''); @endphp
                                            <div class="t-step {{ $cls }}">
                                                <div class="t-circle">@if($step > $s)✓@else{{ $info['icon'] }}@endif</div>
                                                <div class="t-label">{{ $info['label'] }}</div>
                                                <div class="t-dot"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="{{ ($booking->status === 'completed' && !$isFullyPaid) ? 'warn-text' : 'hint-text' }}">
                                        @if($step===1) بانتظار مراجعة الطلب من الفريق
                                        @elseif($step===2) تم تأكيد موعدك، سيتواصل معك الفريق قريباً
                                        @elseif($step===3 && $booking->status==='completed' && !$isFullyPaid) ⚠️ يجب إتمام الدفع الكامل لاستلام المشروع
                                        @elseif($step===3) المشروع قيد التصوير والتنفيذ الآن
                                        @elseif($step===4) المشروع مكتمل ✨ شكراً لثقتك
                                        @endif
                                    </p>
                                @else
                                    <div style="margin-bottom:14px;padding:10px 14px;border-radius:12px;border:1px solid rgba(239,68,68,.2);background:rgba(239,68,68,.05);font-size:12px;color:rgba(248,113,113,.8);">
                                        ⚠️ تم إلغاء هذا الحجز. تواصل معنا إذا كان هناك استفسار.
                                    </div>
                                @endif

                                @if($total > 0)
                                    <div class="pay-wrap">
                                        <div class="pay-row"><span class="pay-label">المدفوعات</span><span class="pay-val">{{ number_format($paid) }} / {{ number_format($total) }} دج</span></div>
                                        <div class="bar-bg"><div class="bar-fill" style="width:{{ $payPercent }}%"></div></div>
                                        <div class="pay-foot">
                                            <span class="pay-paid">مدفوع: {{ $payPercent }}%</span>
                                            @if($remaining > 0)<span class="pay-rest">متبقي: {{ number_format($remaining) }} دج</span>
                                            @else<span class="pay-done">✓ مسدّد بالكامل</span>@endif
                                        </div>
                                    </div>
                                @endif

                                <div class="bc-cta">
                                    <span class="bc-cta-text">سجّل دخولك لمشاهدة الصور والفيديو</span>
                                    <a href="{{ route('client.login') }}" class="bc-cta-btn">
                                        منطقة العملاء <i class="bi bi-arrow-left" style="font-size:10px"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state fade-up fu-d3">
                        <p style="font-size:2rem;margin-bottom:.75rem">🔍</p>
                        <p style="font-weight:800;color:#fff;margin-bottom:.35rem">لم نجد أي حجوزات</p>
                        <p style="font-size:13px;color:rgba(255,255,255,.5)">تأكد من الرقم أو <a href="{{ route('booking') }}">احجز الآن</a></p>
                    </div>
                @endif
            @endisset

            <p class="login-hint fade-up fu-d3">
                لديك حساب؟ <a href="{{ route('client.login') }}">سجّل الدخول</a>
                &nbsp;·&nbsp;
                لا تملك حجزاً؟ <a href="{{ route('booking') }}">احجز الآن</a>
            </p>
        </div>
    </div>

    {{-- ══ VISUAL PANEL — يمين (order:2) ══ --}}
    <div class="vis-panel" aria-hidden="true">
        <div class="vis-glow-top"></div>
        <div class="vis-glow-bottom"></div>
        <div class="vis-grid"></div>
        <div class="vis-inner">
            <a href="{{ url('/') }}" class="vis-logo">
                <span class="vis-logo-text">ONX</span>
                <span class="vis-logo-dot"></span>
            </a>

            <div class="preview-wrap">
                <div class="preview-card">
                    <div class="pc-head">
                        <span class="pc-head-title">تتبع المراحل</span>
                        <span class="pc-badge">مباشر</span>
                    </div>
                    <div class="pc-stats">
                        <div class="pc-stat"><div class="pc-stat-val">📩</div><div class="pc-stat-lbl">استلام</div></div>
                        <div class="pc-stat"><div class="pc-stat-val">🎬</div><div class="pc-stat-lbl">تنفيذ</div></div>
                        <div class="pc-stat"><div class="pc-stat-val">🏁</div><div class="pc-stat-lbl">تسليم</div></div>
                    </div>
                    <div class="pc-bars">
                        <div class="pc-bar-item"><span class="pc-bar-lbl">التصوير</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:100%"></div></div></div>
                        <div class="pc-bar-item"><span class="pc-bar-lbl">المونتاج</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:70%;animation-delay:.15s"></div></div></div>
                        <div class="pc-bar-item"><span class="pc-bar-lbl">التسليم</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:30%;animation-delay:.3s"></div></div></div>
                    </div>
                </div>

                <p class="ben-label">ماذا يمكنك أن تعرف؟</p>
                <ul class="ben-list">
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-map"></i></span><div class="ben-body"><strong>مراحل المشروع</strong><span>اعرف أين وصل مشروعك بدقة.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-cash-stack"></i></span><div class="ben-body"><strong>حالة الدفع</strong><span>المبلغ المدفوع والمتبقي.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-calendar-check"></i></span><div class="ben-body"><strong>تواريخ المشروع</strong><span>موعد الحفل أو الإعلان.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-person-check"></i></span><div class="ben-body"><strong>دخول كامل</strong><span>سجّل دخولك لمشاهدة الصور والفيديو.</span></div></li>
                </ul>
            </div>

            <div class="vis-bottom">
                <div class="vis-bottom-line"></div>
                <span class="vis-bottom-text">onx-edge.com</span>
                <div class="vis-bottom-line"></div>
            </div>
        </div>
    </div>

</div>
</body>
</html>