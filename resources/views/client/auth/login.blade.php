<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول — ONX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body { min-height: 100%; font-family: 'Cairo', sans-serif; background: #060606; color: #fff; -webkit-font-smoothing: antialiased; }

        /* ── LAYOUT ── */
        .page { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }
        @media (max-width: 1023px) { .page { grid-template-columns: 1fr; } .vis-panel { display: none; } }

        /* ── LEFT — VISUAL ── */
        .vis-panel { position: relative; background: #080808; overflow: hidden; display: flex; flex-direction: column; }
        .vis-glow-top { position: absolute; top: -80px; left: -80px; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, rgba(249,115,22,.18) 0%, transparent 70%); pointer-events: none; }
        .vis-glow-bottom { position: absolute; bottom: -100px; right: -60px; width: 380px; height: 380px; border-radius: 50%; background: radial-gradient(circle, rgba(234,88,12,.1) 0%, transparent 70%); pointer-events: none; }
        .vis-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 40px 40px; }
        .vis-inner { position: relative; z-index: 1; display: flex; flex-direction: column; height: 100%; padding: 2.5rem; }
        .vis-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .vis-logo-text { font-size: 20px; font-weight: 900; letter-spacing: .18em; color: #fff; }
        .vis-logo-dot { width: 9px; height: 9px; border-radius: 50%; background: #f97316; box-shadow: 0 0 18px rgba(249,115,22,.85); display: inline-block; flex-shrink: 0; }
        .preview-wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 0; }
        .preview-card { width: 100%; max-width: 340px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09); border-radius: 20px; padding: 1.25rem; animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .pc-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .875rem; padding-bottom: .75rem; border-bottom: 1px solid rgba(255,255,255,.07); }
        .pc-head-title { font-size: 12.5px; font-weight: 800; color: rgba(255,255,255,.8); }
        .pc-badge { font-size: 9.5px; font-weight: 700; padding: 3px 9px; border-radius: 20px; background: rgba(249,115,22,.17); color: #fb923c; }
        .pc-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: .4rem; margin-bottom: .875rem; }
        .pc-stat { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: .55rem .4rem; text-align: center; }
        .pc-stat-val { font-size: 17px; font-weight: 900; color: #fff; line-height: 1; margin-bottom: 3px; }
        .pc-stat-lbl { font-size: 9px; color: rgba(255,255,255,.38); font-weight: 700; }
        .pc-bars { display: flex; flex-direction: column; gap: .4rem; }
        .pc-bar-item { display: flex; align-items: center; gap: .5rem; }
        .pc-bar-lbl { font-size: 10.5px; color: rgba(255,255,255,.45); min-width: 52px; }
        .pc-bar-track { flex:1; height: 4px; background: rgba(255,255,255,.07); border-radius: 10px; overflow:hidden; }
        .pc-bar-fill { height: 100%; background: linear-gradient(90deg, #f97316, #fb923c); border-radius: 10px; transform-origin: right; animation: barGrow 1.4s cubic-bezier(.34,1.56,.64,1) both; }
        @keyframes barGrow { from{transform:scaleX(0)} to{transform:scaleX(1)} }
        .ben-label { font-size: 10px; font-weight: 700; letter-spacing: .1em; color: rgba(255,255,255,.28); margin-bottom: .75rem; }
        .ben-list { display: flex; flex-direction: column; gap: .65rem; list-style: none; }
        .ben-item { display: flex; align-items: flex-start; gap: .7rem; }
        .ben-icon { flex-shrink: 0; width: 32px; height: 32px; border-radius: 10px; background: rgba(249,115,22,.13); border: 1px solid rgba(249,115,22,.22); display: flex; align-items: center; justify-content: center; color: #fb923c; font-size: 14px; margin-top: 1px; }
        .ben-body strong { display: block; font-size: 12.5px; font-weight: 800; color: rgba(255,255,255,.82); margin-bottom: 1px; }
        .ben-body span { font-size: 11.5px; color: rgba(255,255,255,.42); line-height: 1.45; }
        .vis-bottom { margin-top: 2rem; display: flex; align-items: center; gap: .5rem; }
        .vis-bottom-line { flex:1; height:1px; background:rgba(255,255,255,.07); }
        .vis-bottom-text { font-size: 11px; color: rgba(255,255,255,.2); white-space: nowrap; }

        /* ── RIGHT — FORM PANEL ── */
        .form-panel { display: flex; flex-direction: column; justify-content: flex-start; align-items: center; background: #060606; padding: 5rem 2rem 4rem; position: relative; }
        .form-panel::before { content: ''; position: absolute; top: -60px; right: -60px; width: 320px; height: 320px; border-radius: 50%; background: radial-gradient(circle, rgba(249,115,22,.07) 0%, transparent 70%); pointer-events: none; }
        .form-inner { width: 100%; max-width: 400px; position: relative; z-index: 1; }
        .back-link { position: absolute; top: 1.5rem; left: 1.5rem; display: flex; align-items: center; gap: .4rem; font-size: 12px; font-weight: 700; color: rgba(255,255,255,.3); text-decoration: none; transition: color .15s; z-index: 2; }
        .back-link:hover { color: rgba(255,255,255,.65); }
        .mobile-logo { display: none; align-items: center; gap: 8px; margin-bottom: 2rem; text-decoration: none; }
        .mobile-logo-text { font-size: 18px; font-weight: 900; letter-spacing: .18em; color: #fff; }
        .mobile-logo-dot { width: 8px; height: 8px; border-radius: 50%; background: #f97316; box-shadow: 0 0 14px rgba(249,115,22,.8); }
        @media (max-width: 1023px) { .mobile-logo { display: flex; } }

        /* ── WELCOME ── */
        .welcome { margin-bottom: 1.75rem; }
        .welcome h1 { font-size: 26px; font-weight: 900; color: #fff; margin-bottom: .4rem; line-height: 1.2; }
        .welcome p { font-size: 13.5px; color: rgba(255,255,255,.42); line-height: 1.6; }

        /* ── ALERTS ── */
        .error-alert { margin-bottom: 1.25rem; padding: .875rem 1rem; border-radius: 14px; border: 1px solid rgba(239,68,68,.28); background: rgba(239,68,68,.07); font-size: 13px; color: #fca5a5; }
        .error-alert p { font-weight: 700; }
        .success-alert { margin-bottom: 1rem; padding: .75rem 1rem; border-radius: 12px; border: 1px solid rgba(34,197,94,.22); background: rgba(34,197,94,.07); font-size: 13px; color: #86efac; font-weight: 700; }

        /* ── MAIN LOGIN CARD ── */
        .login-card { background: rgba(255,255,255,.035); border: 1px solid rgba(255,255,255,.08); border-radius: 24px; padding: 1.75rem; transition: border-color .3s; }
        .login-card.recovery-open { border-color: rgba(249,115,22,.2); border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
        .card-eyebrow { font-size: 10px; font-weight: 700; letter-spacing: .1em; color: rgba(255,255,255,.28); margin-bottom: 1.25rem; }

        /* ── FIELDS ── */
        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: 12.5px; font-weight: 700; color: rgba(255,255,255,.72); margin-bottom: .45rem; }
        .field-input { width: 100%; background: rgba(255,255,255,.04); border: 1.5px solid rgba(255,255,255,.1); border-radius: 14px; padding: .75rem 1rem; color: #fff; font-size: 13.5px; font-family: 'Cairo', sans-serif; outline: none; transition: border-color .2s, background .2s, box-shadow .2s; }
        .field-input::placeholder { color: rgba(255,255,255,.22); }
        .field-input:hover { border-color: rgba(255,255,255,.18); }
        .field-input:focus { border-color: rgba(249,115,22,.55); background: rgba(249,115,22,.03); box-shadow: 0 0 0 4px rgba(249,115,22,.09); }
        .field-input.has-error { border-color: rgba(239,68,68,.45); }
        .field-error { margin-top: .35rem; font-size: 11.5px; color: #f87171; }

        /* ── PW WRAP ── */
        .pw-wrap { position: relative; }
        .pw-wrap .field-input { padding-left: 3rem; }

        /* ── [تحسين 3] pw-toggle + tooltip ── */
        .pw-toggle {
            position: absolute; left: .65rem; top: 50%; transform: translateY(-50%);
            width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; color: rgba(255,255,255,.35);
            border-radius: 10px; cursor: pointer; font-size: 15px;
            transition: color .15s, background .15s;
        }
        .pw-toggle:hover { color: #fff; background: rgba(255,255,255,.07); }
        .pw-toggle::after {
            content: attr(data-tip);
            position: absolute;
            bottom: calc(100% + 7px);
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            background: rgba(15,15,15,.96);
            color: rgba(255,255,255,.82);
            font-size: 10.5px; font-weight: 700;
            font-family: 'Cairo', sans-serif;
            white-space: nowrap;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.09);
            pointer-events: none;
            opacity: 0;
            transition: opacity .18s ease, transform .18s ease;
            z-index: 20;
        }
        .pw-toggle:hover::after { opacity: 1; transform: translateX(-50%) translateY(0); }

        .bottom-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .5rem; }
        .remember-label { display: flex; align-items: center; gap: .45rem; font-size: 12.5px; color: rgba(255,255,255,.52); cursor: pointer; }
        .remember-label input { accent-color: #f97316; width: 15px; height: 15px; cursor: pointer; }

        /* ── FORGOT TRIGGER ── */
        .forgot-trigger {
            display: flex; align-items: center; gap: .35rem; font-size: 12.5px; font-weight: 700;
            color: #fb923c; background: none; border: none; cursor: pointer;
            font-family: 'Cairo', sans-serif; padding: 0; transition: color .15s; line-height: 1;
        }
        .forgot-trigger:hover { color: #fdba74; }
        .forgot-trigger .ft-arrow { font-size: 11px; transition: transform .3s ease; }
        .forgot-trigger.open .ft-arrow { transform: rotate(180deg); }

        /* ── PRIMARY BUTTON ── */
        .btn-primary {
            width: 100%; padding: .875rem; border-radius: 100px; border: none;
            background: #f97316; color: #000; font-size: 15px; font-weight: 900;
            font-family: 'Cairo', sans-serif; cursor: pointer;
            box-shadow: 0 4px 24px rgba(249,115,22,.26);
            transition: background .2s, transform .1s, box-shadow .2s, opacity .25s;
            display: flex; align-items: center; justify-content: center; gap: .45rem;
        }
        .btn-primary:hover:not(:disabled) { background: #fb923c; box-shadow: 0 6px 30px rgba(249,115,22,.36); }
        .btn-primary:active:not(:disabled) { transform: scale(0.98); }
        /* [تحسين 6] disabled until all rules pass */
        .btn-primary:disabled { opacity: .38; cursor: not-allowed; transform: none !important; box-shadow: none; }
        .btn-spinner { display: none; width: 17px; height: 17px; border: 2.5px solid rgba(0,0,0,.22); border-top-color: #000; border-radius: 50%; animation: spin .7s linear infinite; flex-shrink: 0; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-primary.loading .btn-spinner { display: block; }
        .btn-primary.loading .btn-label { opacity: .7; }

        /* ── FLIP PANEL: الـ forgot يحل محل الفورم ── */
        .recovery-accordion {
            display: none;
            border: 1px solid rgba(249,115,22,.15);
            border-radius: 24px;
            background: rgba(249,115,22,.02);
        }
        .recovery-accordion.open {
            display: block;
            animation: flipIn .32s cubic-bezier(.4,0,.2,1);
        }
        @keyframes flipIn {
            from { opacity:0; transform: translateY(8px) scale(.98); }
            to   { opacity:1; transform: translateY(0)   scale(1); }
        }
        .recovery-inner { padding: 1.5rem 1.75rem 1.75rem; }

        /* إخفاء الكارت عند فتح الـ accordion */
        #login-card.hidden-for-recovery {
            display: none !important;
        }

        /* step pills */
        .step-pills { display: flex; align-items: center; gap: .35rem; margin-bottom: 1.25rem; }
        .step-pill { height: 4px; border-radius: 100px; background: rgba(255,255,255,.1); flex: 1; transition: background .3s; }
        .step-pill.active { background: #f97316; }
        .step-pill.done   { background: rgba(34,197,94,.5); }

        #rec-step-success { display: none; }

        .otp-instruction { font-size: 12px; color: rgba(255,255,255,.55); text-align: center; margin-bottom: 1rem; padding: .6rem .75rem; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; line-height: 1.5; }
        .otp-instruction strong { color: #fb923c; }

        /* ── OTP boxes ── */
        .otp-row { display: flex !important; gap: .35rem; justify-content: center; align-items: center; direction: ltr; margin-bottom: .75rem; min-height: 52px; flex-wrap: nowrap; overflow: visible; }
        .otp-box { width: 42px; height: 50px; min-width: 38px; background: rgba(255,255,255,.08); border: 1.5px solid rgba(255,255,255,.18); border-radius: 10px; text-align: center; font-size: 20px; font-weight: 900; color: #fff; font-family: 'Cairo', sans-serif; outline: none; transition: border-color .2s, box-shadow .2s; caret-color: #f97316; -webkit-appearance: none; appearance: none; flex-shrink: 0; }
        .otp-box:focus { border-color: rgba(249,115,22,.6); box-shadow: 0 0 0 3px rgba(249,115,22,.12); }
        .otp-box.err { border-color: rgba(239,68,68,.5); background: rgba(239,68,68,.06); }
        @media (max-width: 380px) { .otp-box { width: 36px; height: 46px; min-width: 32px; font-size: 18px; } .otp-row { gap: .25rem; } }

        .countdown-row { text-align: center; font-size: 11.5px; color: rgba(255,255,255,.28); margin-bottom: 1rem; }
        .countdown-row b { color: rgba(249,115,22,.75); }

        .resend-row { display: flex; align-items: center; justify-content: center; gap: .4rem; margin-top: .875rem; font-size: 12px; color: rgba(255,255,255,.3); }
        .resend-btn { background: none; border: none; color: #fb923c; font-weight: 700; font-family: 'Cairo', sans-serif; font-size: 12px; cursor: pointer; padding: 0; transition: color .15s; }
        .resend-btn:hover { color: #fdba74; }
        .resend-btn:disabled { opacity: .45; cursor: not-allowed; }

        .otp-verified-msg { font-size: 12px; color: rgba(34,197,94,.9); margin-bottom: 1rem; padding: .5rem 0; font-weight: 700; }

        .back-step { display: flex; align-items: center; gap: .3rem; font-size: 12px; color: rgba(255,255,255,.3); background: none; border: none; cursor: pointer; font-family: 'Cairo', sans-serif; padding: 0; margin-bottom: 1rem; transition: color .15s; }
        .back-step:hover { color: rgba(255,255,255,.6); }

        .success-mini { text-align: center; padding: .5rem 0 .25rem; }
        .success-mini .check-circle { width: 52px; height: 52px; border-radius: 50%; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); display: flex; align-items: center; justify-content: center; margin: 0 auto .875rem; font-size: 22px; color: #86efac; }
        .success-mini h3 { font-size: 17px; font-weight: 900; color: #fff; margin-bottom: .35rem; }
        .success-mini p { font-size: 12.5px; color: rgba(255,255,255,.42); margin-bottom: 1rem; }
        .progress-bar-wrap { height: 3px; border-radius: 100px; background: rgba(255,255,255,.07); overflow: hidden; }
        .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #f97316, #fb923c); border-radius: 100px; width: 0; transition: width 2s linear; }

        /* ── DIVIDER + FOOTER ── */
        .divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,.07), transparent); margin: 1.4rem 0; }
        .register-hint { text-align: center; font-size: 12.5px; color: rgba(255,255,255,.35); }
        .register-hint a { color: #fb923c; font-weight: 700; text-decoration: none; transition: color .15s; }
        .register-hint a:hover { color: #fdba74; }
        .form-note { margin-top: 1rem; text-align: center; font-size: 11.5px; color: rgba(255,255,255,.2); line-height: 1.55; }

        /* ── FADE UP ── */
        .fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .5s ease forwards; }
        .fu-d1{animation-delay:.06s} .fu-d2{animation-delay:.14s} .fu-d3{animation-delay:.22s}
        @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }

        #rec-password-block { display: none; }
        #rec-password-block.visible { display: block; animation: fadeIn .3s ease; }

        /* ══════════════════════════════════════════════
           LIVE PASSWORD VALIDATION
        ══════════════════════════════════════════════ */
        /* [تحسين 5] slideDown للـ rules box */
        .pw-rules {
            margin-top: .65rem;
            display: flex; flex-direction: column; gap: .32rem;
            padding: .8rem 1rem;
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 13px;
        }
        .pw-rules.slide-in {
            animation: rulesDown .28s cubic-bezier(.4,0,.2,1);
        }
        @keyframes rulesDown {
            from { opacity:0; transform:translateY(-8px) scaleY(.94); }
            to   { opacity:1; transform:translateY(0)   scaleY(1); }
        }
        .pw-rule {
            display: flex; align-items: center; gap: .5rem;
            font-size: 12px; color: rgba(255,255,255,.32); font-weight: 600;
            transition: color .22s ease; line-height: 1.4;
        }
        .pw-rule.valid   { color: #86efac; }
        .pw-rule.invalid { color: #f87171; }
        .pw-rule-icon {
            width: 17px; height: 17px; border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; flex-shrink: 0;
            transition: background .22s ease, border-color .22s ease, color .22s ease;
            color: rgba(255,255,255,.2);
        }
        .pw-rule.valid   .pw-rule-icon { background: rgba(34,197,94,.15); border-color: rgba(34,197,94,.45); color: #86efac; }
        .pw-rule.invalid .pw-rule-icon { background: rgba(239,68,68,.1);  border-color: rgba(239,68,68,.4);  color: #f87171; }

        /* strength bar */
        .pw-strength-wrap { margin-top: .6rem; padding-top: .6rem; border-top: 1px solid rgba(255,255,255,.06); }
        .pw-strength-label { display: flex; justify-content: space-between; font-size: 11px; color: rgba(255,255,255,.28); margin-bottom: .35rem; font-weight: 700; }
        .pw-strength-label .strength-val { transition: color .35s ease; font-weight: 800; }
        .pw-strength-track { height: 4px; background: rgba(255,255,255,.07); border-radius: 100px; overflow: hidden; }
        .pw-strength-fill  { height: 100%; border-radius: 100px; width: 0; transition: width .4s ease, background .4s ease; }

        /* confirm match */
        .pw-match-msg { margin-top: .4rem; font-size: 12px; font-weight: 700; align-items: center; gap: .35rem; display: none; }
        .pw-match-msg.show     { display: flex; animation: fadeIn .2s ease; }
        .pw-match-msg.match    { color: #86efac; }
        .pw-match-msg.no-match { color: #f87171; }
    </style>
</head>
<body>
<div class="page">

    {{-- ══════════════ LEFT — Visual ══════════════ --}}
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
                        <span class="pc-head-title">لوحة التحكم</span>
                        <span class="pc-badge">● مباشر</span>
                    </div>
                    <div class="pc-stats">
                        <div class="pc-stat"><div class="pc-stat-val">4</div><div class="pc-stat-lbl">مشاريع</div></div>
                        <div class="pc-stat"><div class="pc-stat-val">12</div><div class="pc-stat-lbl">ملفات</div></div>
                        <div class="pc-stat"><div class="pc-stat-val">3</div><div class="pc-stat-lbl">فواتير</div></div>
                    </div>
                    <div class="pc-bars">
                        <div class="pc-bar-item"><span class="pc-bar-lbl">التصوير</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:82%"></div></div></div>
                        <div class="pc-bar-item"><span class="pc-bar-lbl">المونتاج</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:56%;animation-delay:.15s"></div></div></div>
                        <div class="pc-bar-item"><span class="pc-bar-lbl">التسليم</span><div class="pc-bar-track"><div class="pc-bar-fill" style="width:31%;animation-delay:.3s"></div></div></div>
                    </div>
                </div>
            </div>
            <div>
                <p class="ben-label">لماذا تسجّل الدخول؟</p>
                <ul class="ben-list">
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-kanban"></i></span><div class="ben-body"><strong>تتبع مشاريعك</strong><span>راقب حالة كل مشروع وحجز.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-folder2-open"></i></span><div class="ben-body"><strong>وصول للملفات</strong><span>استعرض الوسائط والفواتير.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-chat-dots"></i></span><div class="ben-body"><strong>تواصل مع الفريق</strong><span>راسلنا وأدر حسابك بأمان.</span></div></li>
                    <li class="ben-item"><span class="ben-icon"><i class="bi bi-receipt"></i></span><div class="ben-body"><strong>إدارة الفواتير</strong><span>تتبع جميع فواتيرك.</span></div></li>
                </ul>
                <div class="vis-bottom"><div class="vis-bottom-line"></div><span class="vis-bottom-text">onx-edge.com</span><div class="vis-bottom-line"></div></div>
            </div>
        </div>
    </div>

    {{-- ══════════════ RIGHT — Form ══════════════ --}}
    <div class="form-panel">
        <a href="{{ url('/') }}" class="back-link">
            <i class="bi bi-arrow-left"></i>
            العودة للموقع
        </a>

        <div class="form-inner">

            <a href="{{ url('/') }}" class="mobile-logo">
                <span class="mobile-logo-text">ONX</span>
                <span class="mobile-logo-dot"></span>
            </a>

            <div class="welcome fade-up fu-d1">
                <h1>مرحباً بعودتك 👋</h1>
                <p>سجّل دخولك للوصول إلى مشاريعك وملفاتك وفواتيرك.</p>
            </div>

            @if ($errors->any())
            <div role="alert" class="error-alert fade-up fu-d1">
                <p>{{ $errors->first('login') ?: $errors->first('password') ?: 'بيانات الدخول غير صحيحة.' }}</p>
                @if($errors->has('throttle'))
                    <p style="margin-top:.35rem;font-size:12px;opacity:.8;">{{ $errors->first('throttle') }}</p>
                @endif
            </div>
            @endif

            {{-- ════ MAIN LOGIN CARD ════ --}}
            <div class="login-card fade-up fu-d2" id="login-card">
                <p class="card-eyebrow">منطقة العملاء</p>

                <form method="POST" action="{{ route('client.login.post') }}" autocomplete="on" id="login-form">
                    @csrf
                    <div class="field">
                        <label for="login-field">البريد الإلكتروني أو رقم الهاتف</label>
                        <input
                            type="text" id="login-field" name="login"
                            value="{{ old('login') }}" autocomplete="username email" required
                            placeholder="example@email.com أو 05xxxxxxxx"
                            class="field-input @error('login') has-error @enderror"
                        />
                        @error('login')<p class="field-error" role="alert">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="pw-field">كلمة المرور</label>
                        <div class="pw-wrap">
                            <input
                                type="password" id="pw-field" name="password"
                                autocomplete="current-password" required placeholder="••••••••"
                                class="field-input @error('password') has-error @enderror"
                            />
                            {{-- [تحسين 3] tooltip --}}
                            <button type="button" class="pw-toggle" id="pw-toggle"
                                    aria-label="إظهار كلمة المرور" data-tip="إظهار">
                                <i id="eye-show" class="bi bi-eye"></i>
                                <i id="eye-hide" class="bi bi-eye-slash" style="display:none;"></i>
                            </button>
                        </div>
                        @error('password')<p class="field-error" role="alert">{{ $message }}</p>@enderror
                    </div>

                    <div class="bottom-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" value="1"> تذكرني
                        </label>
                        <button type="button" class="forgot-trigger" id="forgot-trigger"
                                aria-expanded="false" aria-controls="recovery-accordion">
                            نسيت كلمة مرورك؟
                            <i class="bi bi-chevron-down ft-arrow"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn-primary" id="login-btn">
                        <span class="btn-spinner"></span>
                        <span class="btn-label">تسجيل الدخول</span>
                    </button>
                </form>
            </div>

            {{-- ════ [تحسين 1] ACCORDION — margin-top:-1px ════ --}}
            <div class="recovery-accordion" id="recovery-accordion"
                 role="region" aria-label="استرجاع كلمة المرور">
                <div class="recovery-inner">

                    <div class="step-pills">
                        <div class="step-pill active" id="pill-1"></div>
                        <div class="step-pill" id="pill-2"></div>
                        <div class="step-pill" id="pill-3"></div>
                    </div>

                    {{-- ── STEP 1 ── --}}
                    <div id="rec-step-1">
                        <p style="font-size:12.5px;color:rgba(255,255,255,.5);margin-bottom:.875rem;line-height:1.5;">
                            أدخل بريدك الإلكتروني وسنرسل لك كود التحقق.
                        </p>
                        <div id="rec-error-1" role="alert" aria-live="polite"></div>
                        <div class="field">
                            <label for="rec-email">البريد الإلكتروني</label>
                            <input type="email" id="rec-email" autocomplete="email"
                                   placeholder="example@email.com" class="field-input"/>
                            <p id="rec-email-err" class="field-error" style="display:none;" role="alert"></p>
                        </div>
                        <button type="button" class="btn-primary" id="send-otp-btn">
                            <span class="btn-spinner"></span>
                            <span class="btn-label">إرسال الكود</span>
                        </button>
                    </div>

                    {{-- رأس الخطوة 2 --}}
                    <div id="rec-step-2-head" style="display:none;">
                        <button type="button" class="back-step" id="back-to-step1">
                            <i class="bi bi-arrow-right" style="font-size:11px;"></i>
                            تغيير البريد
                        </button>
                        <div id="rec-error-2" role="alert" aria-live="polite"></div>
                    </div>

                    {{-- OTP --}}
                    <div id="rec-otp-block" style="display:none;">
                        <p class="otp-instruction">
                            تحقّق من بريدك الإلكتروني — أدخل الكود المكوّن من <strong>6 أرقام</strong> في المربعات أدناه.
                        </p>
                        <div class="field" style="margin-bottom:.5rem;">
                            <label style="text-align:center;display:block;margin-bottom:.5rem;font-size:12.5px;font-weight:700;color:rgba(255,255,255,.72);">
                                الكود المرسل إلى <span id="otp-sent-to" style="color:#fb923c;"></span>
                            </label>
                            <div class="otp-row" id="otp-row" role="group" aria-label="خانات كود التحقق">
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="0" autocomplete="one-time-code" aria-label="الرقم الأول"/>
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="1" autocomplete="off" aria-label="الرقم الثاني"/>
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="2" autocomplete="off" aria-label="الرقم الثالث"/>
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="3" autocomplete="off" aria-label="الرقم الرابع"/>
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="4" autocomplete="off" aria-label="الرقم الخامس"/>
                                <input type="text" inputmode="numeric" maxlength="1" class="otp-box" data-i="5" autocomplete="off" aria-label="الرقم السادس"/>
                            </div>
                            <div class="countdown-row">الكود صالح لـ <b id="countdown">10:00</b></div>
                        </div>
                        <div class="resend-row">
                            لم يصلك الكود؟
                            <button type="button" class="resend-btn" id="resend-btn">إعادة الإرسال</button>
                            <span id="resend-cd" style="font-size:11px;display:none;">(<span id="resend-sec">120</span>ث)</span>
                        </div>
                    </div>

                    {{-- ── كلمة المرور الجديدة ── --}}
                    <div id="rec-password-block">
                        <p class="otp-verified-msg">✓ تم التحقق من الكود. أدخل كلمة المرور الجديدة.</p>

                        <div class="field">
                            <label for="new-pw">كلمة المرور الجديدة</label>
                            <div class="pw-wrap">
                                <input type="password" id="new-pw" autocomplete="new-password"
                                       placeholder="أدخل كلمة مرور قوية" class="field-input"/>
                                {{-- [تحسين 3] tooltip --}}
                                <button type="button" class="pw-toggle" id="pw-t2"
                                        aria-label="إظهار" data-tip="إظهار">
                                    <i id="eye-t2" class="bi bi-eye"></i>
                                    <i id="eye-off-t2" class="bi bi-eye-slash" style="display:none;"></i>
                                </button>
                            </div>
                            <p id="pw-new-err" class="field-error" style="display:none;" role="alert"></p>

                            {{-- [تحسين 5] Live Rules Box --}}
                            <div class="pw-rules" id="pw-rules" style="display:none;">
                                <div class="pw-rule" id="rule-len">
                                    <span class="pw-rule-icon"><i class="bi bi-check2"></i></span>
                                    8 أحرف على الأقل
                                </div>
                                <div class="pw-rule" id="rule-upper">
                                    <span class="pw-rule-icon"><i class="bi bi-check2"></i></span>
                                    حرف كبير واحد (A-Z)
                                </div>
                                <div class="pw-rule" id="rule-lower">
                                    <span class="pw-rule-icon"><i class="bi bi-check2"></i></span>
                                    حرف صغير واحد (a-z)
                                </div>
                                <div class="pw-rule" id="rule-num">
                                    <span class="pw-rule-icon"><i class="bi bi-check2"></i></span>
                                    رقم واحد على الأقل (0-9)
                                </div>
                                <div class="pw-rule" id="rule-sym">
                                    <span class="pw-rule-icon"><i class="bi bi-check2"></i></span>
                                    رمز خاص (!@#$%^&*)
                                </div>
                                <div class="pw-strength-wrap">
                                    <div class="pw-strength-label">
                                        <span>قوة كلمة المرور</span>
                                        <span class="strength-val" id="strength-text">—</span>
                                    </div>
                                    <div class="pw-strength-track">
                                        <div class="pw-strength-fill" id="strength-fill"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label for="confirm-pw">تأكيد كلمة المرور</label>
                            <div class="pw-wrap">
                                <input type="password" id="confirm-pw" autocomplete="new-password"
                                       placeholder="أعد إدخال كلمة المرور" class="field-input"/>
                                {{-- [تحسين 3] tooltip --}}
                                <button type="button" class="pw-toggle" id="pw-t3"
                                        aria-label="إظهار" data-tip="إظهار">
                                    <i id="eye-t3" class="bi bi-eye"></i>
                                    <i id="eye-off-t3" class="bi bi-eye-slash" style="display:none;"></i>
                                </button>
                            </div>
                            <div class="pw-match-msg" id="pw-match-msg">
                                <i class="bi" id="match-icon"></i>
                                <span id="match-text"></span>
                            </div>
                        </div>

                        {{-- [تحسين 6] disabled حتى تكتمل الشروط والتطابق --}}
                        <button type="button" class="btn-primary" id="reset-btn" disabled>
                            <span class="btn-spinner"></span>
                            <span class="btn-label">تأكيد وتسجيل الدخول</span>
                        </button>
                    </div>

                    {{-- ── STEP 3: نجاح ── --}}
                    <div id="rec-step-success" class="success-mini">
                        <div class="check-circle"><i class="bi bi-check-lg"></i></div>
                        <h3>تم تغيير كلمة المرور!</h3>
                        <p>جارٍ تحويلك للوحة التحكم...</p>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill" id="success-bar"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="divider" style="margin-top:1.4rem;"></div>
            <p class="register-hint fade-up fu-d3">
                لا تملك حساباً؟ <a href="{{ url('/booking') }}">ابدأ مشروعك الآن</a>
            </p>
            <p class="form-note fade-up fu-d3">
                بعد الحجز يمكنك تعيين كلمة المرور من صفحة التأكيد<br>أو عبر الرابط الذي نرسله إليك.
            </p>

        </div>
    </div>

</div>

<script>
(function () {
    'use strict';

    var CSRF = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var $    = function (id) { return document.getElementById(id); };

    /* ── DOM refs ── */
    var loginForm      = $('login-form');
    var loginBtn       = $('login-btn');
    var trigger        = $('forgot-trigger');
    var accordion      = $('recovery-accordion');
    var card           = $('login-card');
    var recStep1       = $('rec-step-1');
    var recStep2Head   = $('rec-step-2-head');
    var recOtpBlock    = $('rec-otp-block');
    var recPwBlock     = $('rec-password-block');
    var recStepSuccess = $('rec-step-success');
    var recError1      = $('rec-error-1');
    var recError2      = $('rec-error-2');
    var recEmailEl     = $('rec-email');
    var recEmailErr    = $('rec-email-err');
    var otpSentTo      = $('otp-sent-to');
    var countdownEl    = $('countdown');
    var newPwEl        = $('new-pw');
    var confirmPwEl    = $('confirm-pw');
    var pwNewErr       = $('pw-new-err');
    var resetBtn       = $('reset-btn');
    var resendBtn      = $('resend-btn');
    var resendCd       = $('resend-cd');
    var resendSec      = $('resend-sec');
    var successBar     = $('success-bar');
    var otpBoxes       = document.querySelectorAll('.otp-box');

    var verifyAbort = null, sendAbort = null, resendAbort = null, resetAbort = null;

    /* ══════════════════════════════════════════
       LIVE PASSWORD VALIDATION
    ══════════════════════════════════════════ */
    var pwRulesEl    = $('pw-rules');
    var strengthFill = $('strength-fill');
    var strengthText = $('strength-text');
    var matchMsg     = $('pw-match-msg');
    var matchIcon    = $('match-icon');
    var matchTextEl  = $('match-text');

    var RULES = [
        { id: 'rule-len',   test: function(v){ return v.length >= 8; } },
        { id: 'rule-upper', test: function(v){ return /[A-Z]/.test(v); } },
        { id: 'rule-lower', test: function(v){ return /[a-z]/.test(v); } },
        { id: 'rule-num',   test: function(v){ return /[0-9]/.test(v); } },
        { id: 'rule-sym',   test: function(v){ return /[!@#$%^&*()\-_=+\[\]{};':"\\|,.<>\/?`~]/.test(v); } },
    ];

    var STRENGTH = [
        { label: '—',      color: 'rgba(255,255,255,.12)', pct: '0%'   },
        { label: 'ضعيفة',  color: '#ef4444',               pct: '20%'  },
        { label: 'مقبولة', color: '#f97316',               pct: '40%'  },
        { label: 'جيدة',   color: '#eab308',               pct: '65%'  },
        { label: 'قوية',   color: '#22c55e',               pct: '85%'  },
        { label: 'ممتازة', color: '#10b981',               pct: '100%' },
    ];

    /* [تحسين 6] تفعيل/تعطيل زر الإرسال */
    function syncResetBtn() {
        var pw  = newPwEl     ? newPwEl.value     : '';
        var cfm = confirmPwEl ? confirmPwEl.value  : '';
        var ok  = RULES.every(function(r){ return r.test(pw); }) && pw === cfm && cfm.length > 0;
        if (resetBtn) resetBtn.disabled = !ok;
    }

    function updatePwRules() {
        var val    = newPwEl.value;
        var passed = 0;

        RULES.forEach(function(rule) {
            var ok = rule.test(val);
            var el = $(rule.id);
            if (!el) return;
            if (val.length === 0) { el.classList.remove('valid','invalid'); }
            else { el.classList.toggle('valid', ok); el.classList.toggle('invalid', !ok); }
            if (ok) passed++;
        });

        var lvl = val.length === 0 ? 0 : Math.min(passed + (val.length >= 12 ? 1 : 0), 5);
        var s   = STRENGTH[lvl];
        if (strengthFill) { strengthFill.style.width = s.pct; strengthFill.style.background = s.color; }
        if (strengthText) { strengthText.textContent = s.label; strengthText.style.color = s.color; }

        /* [تحسين 5] slideDown animation عند الظهور الأول */
        if (pwRulesEl) {
            if (val.length > 0 && pwRulesEl.style.display === 'none') {
                pwRulesEl.style.display = '';
                pwRulesEl.classList.remove('slide-in');
                void pwRulesEl.offsetWidth; /* reflow */
                pwRulesEl.classList.add('slide-in');
            } else if (val.length === 0) {
                pwRulesEl.style.display = 'none';
                pwRulesEl.classList.remove('slide-in');
            }
        }
        updateConfirmMatch();
        syncResetBtn();
    }

    function updateConfirmMatch() {
        if (!matchMsg || !confirmPwEl) return;
        var pw  = newPwEl ? newPwEl.value : '';
        var cfm = confirmPwEl.value;
        if (cfm.length === 0) {
            matchMsg.classList.remove('show','match','no-match');
        } else {
            var ok = pw === cfm && pw.length > 0;
            matchMsg.classList.add('show');
            matchMsg.classList.toggle('match',    ok);
            matchMsg.classList.toggle('no-match', !ok);
            if (matchIcon)   matchIcon.className    = ok ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill';
            if (matchTextEl) matchTextEl.textContent = ok ? 'كلمتا المرور متطابقتان ✓' : 'كلمتا المرور غير متطابقتين';
        }
        syncResetBtn();
    }

    if (newPwEl)     newPwEl.addEventListener('input',     updatePwRules);
    if (confirmPwEl) confirmPwEl.addEventListener('input', updateConfirmMatch);

    function resetPwRules() {
        RULES.forEach(function(r){ var el=$(r.id); if(el) el.classList.remove('valid','invalid'); });
        if (strengthFill) { strengthFill.style.width='0'; strengthFill.style.background=''; }
        if (strengthText) { strengthText.textContent='—'; strengthText.style.color=''; }
        if (pwRulesEl)    { pwRulesEl.style.display='none'; pwRulesEl.classList.remove('slide-in'); }
        if (matchMsg)     matchMsg.classList.remove('show','match','no-match');
        if (resetBtn)     resetBtn.disabled = true;
    }

    /* ── helpers ── */
    function postJson(url, data, abortCtrl) {
        var opts = {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify(data),
            credentials: 'same-origin',
        };
        if (abortCtrl) opts.signal = abortCtrl.signal;
        return fetch(url, opts).then(function(r){
            if (r.status===419) { window.location.reload(); return Promise.reject(new Error('session_expired')); }
            return r.text().then(function(text){
                var d={};
                try{ d=text?JSON.parse(text):{}; }catch(e){ d={message:r.status+' '+r.statusText}; }
                return { ok:r.ok, status:r.status, data:d };
            });
        });
    }
    function showErr(el,msg)     { if(el) el.innerHTML='<div class="error-alert" role="alert"><p>'+msg+'</p></div>'; }
    function clearErr(el)        { if(el) el.innerHTML=''; }
    function showSuccess(el,msg) { if(el) el.innerHTML='<div class="success-alert" role="alert">'+msg+'</div>'; }
    function maskEmail(e)        { var p=e.split('@'); if(p.length!==2) return e; var n=p[0]; return n.slice(0,2)+'***'+(n.slice(-1)||'')+'@'+p[1]; }

    /* [تحسين 3] pw-toggle + تحديث data-tip ديناميكياً */
    function pwToggle(btnId, inputEl, eyeId, eyeOffId) {
        var btn=$(btnId), eye=$(eyeId), eo=$(eyeOffId);
        if (!btn||!inputEl) return;
        btn.addEventListener('click', function(){
            var showing = inputEl.type==='password';
            inputEl.type      = showing ? 'text' : 'password';
            eye.style.display = showing ? 'none' : '';
            eo.style.display  = showing ? '' : 'none';
            var lbl = showing ? 'إخفاء' : 'إظهار';
            btn.setAttribute('aria-label', lbl);
            btn.setAttribute('data-tip',   lbl);
        });
    }
    pwToggle('pw-toggle', $('pw-field'), 'eye-show',   'eye-hide');
    pwToggle('pw-t2',     newPwEl,       'eye-t2',     'eye-off-t2');
    pwToggle('pw-t3',     confirmPwEl,   'eye-t3',     'eye-off-t3');

    /* ── login submit ── */
    loginForm.addEventListener('submit', function(){ loginBtn.disabled=true; loginBtn.classList.add('loading'); });

    /* ═══════════════════════════════
       [تحسين 1 + 5] ACCORDION
    ═══════════════════════════════ */
    var isOpen = false;

    function openAccordion() {
        isOpen = true;
        // أخفِ الكارت واعرض الـ accordion مكانه
        card.classList.add('hidden-for-recovery');
        card.classList.remove('recovery-open');
        accordion.classList.add('open');
        trigger.classList.add('open');
        trigger.setAttribute('aria-expanded','true');
        setTimeout(function(){
            recEmailEl && recEmailEl.focus();
        }, 80);
    }
    function closeAccordion() {
        isOpen = false;
        // أعد الكارت وأخفِ الـ accordion
        accordion.classList.remove('open');
        card.classList.remove('hidden-for-recovery');
        trigger.classList.remove('open');
        trigger.setAttribute('aria-expanded','false');
    }
    trigger.addEventListener('click', function(){ isOpen ? closeAccordion() : openAccordion(); });

    /* ── step management ── */
    function goStep(n) {
        recStep1.style.display       = n===1 ? '' : 'none';
        recStep2Head.style.display   = n===2 ? '' : 'none';
        recOtpBlock.style.display    = n===2 ? '' : 'none';
        recStepSuccess.style.display = n===3 ? 'block' : 'none';
        ['pill-1','pill-2','pill-3'].forEach(function(id,i){
            var el=$(id); if(!el) return;
            el.classList.remove('active','done');
            if(i+1<n) el.classList.add('done');
            else if(i+1===n) el.classList.add('active');
        });
    }

    var currentEmail='', cdInterval=null, resendTimer=null;

    /* ── SEND OTP ── */
    $('send-otp-btn').addEventListener('click', function(){
        var email = recEmailEl ? recEmailEl.value.trim() : '';
        if(recEmailErr) recEmailErr.style.display='none';
        clearErr(recError1);
        if(!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
            if(recEmailErr){ recEmailErr.textContent='أدخل بريدًا إلكترونيًا صحيحًا.'; recEmailErr.style.display='block'; }
            return;
        }
        var btn=this; btn.disabled=true; btn.classList.add('loading');
        if(sendAbort) sendAbort.abort();
        sendAbort = new AbortController();

        postJson('{{ route("client.forgot-password.send") }}', {email:email}, sendAbort)
            .then(function(res){
                btn.disabled=false; btn.classList.remove('loading');
                if(res.ok && res.data.success){
                    currentEmail=email;
                    if(otpSentTo) otpSentTo.textContent=maskEmail(email);
                    hidePwBlock(); goStep(2); startCountdown(); startResendTimer();
                    /* [تحسين 4] auto-focus أول OTP box */
                    setTimeout(function(){
                        if(otpBoxes[0]){ otpBoxes[0].value=''; otpBoxes[0].focus(); }
                    }, 160);
                } else {
                    var msg=(res.data.errors&&res.data.errors.email)?res.data.errors.email[0]:(res.data.message||'حدث خطأ. حاول مجدداً.');
                    showErr(recError1,msg);
                }
            })
            .catch(function(err){
                if(err&&err.message==='session_expired') return;
                if(err&&err.name==='AbortError') return;
                btn.disabled=false; btn.classList.remove('loading');
                showErr(recError1,'تعذّر الاتصال بالخادم. تحقق من اتصالك بالإنترنت.');
            });
    });

    function showPwBlock() {
        if(recOtpBlock) recOtpBlock.style.display='none';
        recPwBlock.classList.add('visible');
        resetPwRules();
        if(newPwEl) setTimeout(function(){ newPwEl.focus(); }, 80);
    }
    function hidePwBlock() {
        if(recOtpBlock&&recOtpBlock.style.display==='none') recOtpBlock.style.display='';
        recPwBlock.classList.remove('visible');
        if(newPwEl)     newPwEl.value='';
        if(confirmPwEl) confirmPwEl.value='';
        if(pwNewErr)    pwNewErr.style.display='none';
        resetPwRules();
    }

    /* ── back to step 1 ── */
    $('back-to-step1').addEventListener('click', function(){
        if(cdInterval)  clearInterval(cdInterval);
        if(resendTimer) clearInterval(resendTimer);
        if(verifyAbort) verifyAbort.abort();
        clearErr(recError2); clearErr(recError1);
        hidePwBlock();
        otpBoxes.forEach(function(b){ b.value=''; b.classList.remove('err'); });
        goStep(1);
        setTimeout(function(){ recEmailEl.focus(); },50);
    });

    /* ── verify OTP (server-side) ── */
    var verifyInProgress=false;
    function checkOtpAndVerify() {
        var code=getOtp();
        if(code.length!==6||recPwBlock.classList.contains('visible')||verifyInProgress) return;
        verifyInProgress=true; clearErr(recError2);
        if(verifyAbort) verifyAbort.abort();
        verifyAbort=new AbortController();
        postJson('{{ route("client.forgot-password.verify") }}',{code:code},verifyAbort)
            .then(function(res){
                verifyInProgress=false;
                if(res.ok&&res.data.success){ showPwBlock(); }
                else{
                    var msg=(res.data.errors&&res.data.errors.code)?res.data.errors.code[0]:(res.data.message||'الكود غير صحيح.');
                    showErr(recError2,msg);
                    otpBoxes.forEach(function(b){ b.value=''; b.classList.add('err'); });
                    otpBoxes[0].focus();
                }
            })
            .catch(function(err){
                if(err&&err.message==='session_expired') return;
                if(err&&err.name==='AbortError') return;
                verifyInProgress=false;
                showErr(recError2,'تعذّر التحقق. حاول مجدداً.');
                otpBoxes.forEach(function(b){ b.classList.add('err'); });
            });
    }

    /* ── OTP inputs ── */
    otpBoxes.forEach(function(inp,idx){
        inp.addEventListener('input',function(){
            inp.value=inp.value.replace(/\D/,'').slice(-1);
            inp.classList.remove('err');
            if(inp.value&&idx<5) otpBoxes[idx+1].focus();
            if(getOtp().length===6) setTimeout(checkOtpAndVerify,80);
            else hidePwBlock();
        });
        inp.addEventListener('keydown',function(e){
            if(e.key==='Backspace'&&!inp.value&&idx>0){ otpBoxes[idx-1].focus(); otpBoxes[idx-1].value=''; hidePwBlock(); }
        });
        inp.addEventListener('paste',function(e){
            e.preventDefault();
            var p=(e.clipboardData||window.clipboardData).getData('text').replace(/\D/g,'');
            p.split('').forEach(function(c,i){ if(otpBoxes[i]) otpBoxes[i].value=c; });
            otpBoxes[Math.min(p.length,5)].focus();
            if(getOtp().length===6) setTimeout(checkOtpAndVerify,120);
        });
    });
    function getOtp(){ return Array.from(otpBoxes).map(function(b){ return b.value; }).join(''); }

    /* ── countdown ── */
    function startCountdown(){
        if(cdInterval) clearInterval(cdInterval);
        var s=600;
        if(!countdownEl) return;
        countdownEl.style.color='';
        cdInterval=setInterval(function(){
            s--;
            if(s<=0){ clearInterval(cdInterval); countdownEl.textContent='منتهي'; countdownEl.style.color='#f87171'; return; }
            var m=Math.floor(s/60),sec=s%60;
            countdownEl.textContent=(m<10?'0':'')+m+':'+(sec<10?'0':'')+sec;
        },1000);
    }
    function startResendTimer(){
        if(resendTimer) clearInterval(resendTimer);
        var s=120;
        if(!resendBtn||!resendCd||!resendSec) return;
        resendBtn.disabled=true; resendCd.style.display='inline'; resendSec.textContent=s;
        resendTimer=setInterval(function(){
            s--; resendSec.textContent=s;
            if(s<=0){ clearInterval(resendTimer); resendBtn.disabled=false; resendCd.style.display='none'; }
        },1000);
    }

    /* ── RESEND ── */
    resendBtn.addEventListener('click',function(){
        clearErr(recError2); hidePwBlock();
        if(resendAbort) resendAbort.abort();
        resendAbort=new AbortController();
        postJson('{{ route("client.forgot-password.resend") }}',{email:currentEmail},resendAbort)
            .then(function(res){
                otpBoxes.forEach(function(b){ b.value=''; b.classList.remove('err'); });
                /* [تحسين 4] focus بعد إعادة الإرسال */
                setTimeout(function(){ if(otpBoxes[0]) otpBoxes[0].focus(); },80);
                startCountdown(); startResendTimer();
                if(res.ok) showSuccess(recError2,'تم إعادة إرسال الكود إلى بريدك.');
                else showErr(recError2,res.data.message||'حدث خطأ أثناء إعادة الإرسال.');
            })
            .catch(function(err){
                if(err&&err.message==='session_expired') return;
                if(err&&err.name==='AbortError') return;
                showErr(recError2,'تعذّر إعادة الإرسال.');
            });
    });

    /* ── RESET PASSWORD ── */
    resetBtn.addEventListener('click',function(){
        clearErr(recError2);
        if(pwNewErr) pwNewErr.style.display='none';

        var code    =getOtp();
        var password=newPwEl.value;
        var confirm =confirmPwEl.value;

        if(code.length!==6){
            otpBoxes.forEach(function(b){ if(!b.value) b.classList.add('err'); });
            showErr(recError2,'أدخل الكود المكوّن من 6 أرقام أولاً.');
            otpBoxes[0].focus(); return;
        }
        /* [تحسين 6] التحقق يتم في syncResetBtn، الزر أصلاً disabled إذا لم تكتمل الشروط */
        if(!RULES.every(function(r){ return r.test(password); })){
            if(pwNewErr){ pwNewErr.textContent='يجب استيفاء جميع شروط كلمة المرور.'; pwNewErr.style.display='block'; }
            newPwEl.focus(); return;
        }
        if(password!==confirm){
            if(pwNewErr){ pwNewErr.textContent='كلمة المرور وتأكيدها غير متطابقتان.'; pwNewErr.style.display='block'; }
            confirmPwEl.focus(); return;
        }

        var btn=this; btn.disabled=true; btn.classList.add('loading');
        if(resetAbort) resetAbort.abort();
        resetAbort=new AbortController();

        postJson('{{ route("client.forgot-password.reset") }}',{
            code:code, password:password, password_confirmation:confirm,
        },resetAbort)
        .then(function(res){
            btn.classList.remove('loading');
            if(res.ok&&res.data.success){
                if(cdInterval)  clearInterval(cdInterval);
                if(resendTimer) clearInterval(resendTimer);
                goStep(3);
                accordion.scrollIntoView({behavior:'smooth',block:'nearest'});
                setTimeout(function(){ if(successBar) successBar.style.width='100%'; },80);
                setTimeout(function(){
                    window.location.href=res.data.redirect||'{{ route("client.dashboard") }}';
                },2300);
            } else {
                btn.disabled=false;
                var errCode=res.data.errors&&res.data.errors.code     ? res.data.errors.code[0]     : null;
                var errPw  =res.data.errors&&res.data.errors.password ? res.data.errors.password[0] : null;
                var msg    =res.data.message||'حدث خطأ. حاول مجدداً.';
                if(errPw&&!errCode){
                    if(pwNewErr){ pwNewErr.textContent=errPw; pwNewErr.style.display='block'; }
                    newPwEl.focus();
                } else {
                    showErr(recError2,errCode||msg);
                    hidePwBlock();
                    otpBoxes.forEach(function(b){ b.value=''; b.classList.remove('err'); });
                    otpBoxes[0].focus();
                }
            }
        })
        .catch(function(err){
            if(err&&err.message==='session_expired') return;
            if(err&&err.name==='AbortError') return;
            btn.disabled=false; btn.classList.remove('loading');
            showErr(recError2,'تعذّر الاتصال بالخادم.');
        });
    });

    /* ── auto-open ── */
    if(new URLSearchParams(window.location.search).get('forgot')==='1') openAccordion();

    goStep(1);

})();
</script>
</body>
</html>