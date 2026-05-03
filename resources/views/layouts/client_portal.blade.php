<!DOCTYPE html>
<html lang="ar" dir="rtl" class="client-portal-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'بوابة العملاء - ONX')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html.client-portal-page { font-size: 14px; }
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }

        /* ===== Design Tokens ===== */
        :root {
            --event-primary:  #f59e0b;
            --event-dark:     #b45309;
            --event-soft:     #fef3c7;
            --event-border:   #fde68a;

            --ads-primary:    #3b82f6;
            --ads-dark:       #1d4ed8;
            --ads-soft:       #eff6ff;
            --ads-border:     #bfdbfe;

            --text-primary:   #111827;
            --text-secondary: #374151;
            --text-muted:     #6b7280;
            --text-faint:     #9ca3af;

            --card-bg:        #ffffff;
            --card-border:    #e5e7eb;
            --card-radius:    18px;
            --card-shadow:    0 1px 4px rgba(0,0,0,.06), 0 0 0 1px rgba(0,0,0,.03);

            --status-new-bg:            #f3f4f6;  --status-new-text:            #374151;
            --status-confirmed-bg:      #dbeafe;  --status-confirmed-text:      #1d4ed8;
            --status-progress-bg:       #ede9fe;  --status-progress-text:       #7c3aed;
            --status-done-bg:           #dcfce7;  --status-done-text:           #166534;
            --status-cancelled-bg:      #fee2e2;  --status-cancelled-text:      #991b1b;
            --status-unconfirmed-bg:    #fef3c7;  --status-unconfirmed-text:    #92400e;
        }

        /* ===== Utility — service type badges ===== */
        .badge-event {
            display:inline-flex; align-items:center; gap:4px;
            background:var(--event-soft); color:var(--event-dark);
            border:1px solid var(--event-border);
            border-radius:999px; padding:2px 10px; font-size:12px; font-weight:700;
        }
        .badge-ads {
            display:inline-flex; align-items:center; gap:4px;
            background:var(--ads-soft); color:var(--ads-dark);
            border:1px solid var(--ads-border);
            border-radius:999px; padding:2px 10px; font-size:12px; font-weight:700;
        }
        .card-event { border-inline-start: 4px solid var(--event-primary) !important; background: var(--event-soft) !important; }
        .card-ads   { border-inline-start: 4px solid var(--ads-primary)   !important; background: var(--ads-soft)   !important; }

        /* ===== Status badges ===== */
        .s-badge { display:inline-block; border-radius:999px; padding:3px 12px; font-size:12px; font-weight:700; }
        .s-new         { background:var(--status-new-bg);         color:var(--status-new-text); }
        .s-unconfirmed { background:var(--status-unconfirmed-bg); color:var(--status-unconfirmed-text); }
        .s-confirmed   { background:var(--status-confirmed-bg);   color:var(--status-confirmed-text); }
        .s-in_progress { background:var(--status-progress-bg);    color:var(--status-progress-text); }
        .s-completed   { background:var(--status-done-bg);        color:var(--status-done-text); }
        .s-cancelled   { background:var(--status-cancelled-bg);   color:var(--status-cancelled-text); }

        /* ===== Company badge ===== */
        .badge-company {
            display:inline-flex; align-items:center; gap:3px;
            background:#f0fdf4; color:#166534; border:1px solid #bbf7d0;
            border-radius:999px; padding:2px 9px; font-size:11px; font-weight:700;
        }
        .badge-individual {
            display:inline-flex; align-items:center; gap:3px;
            background:#f9fafb; color:#4b5563; border:1px solid #e5e7eb;
            border-radius:999px; padding:2px 9px; font-size:11px; font-weight:700;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f5f6f8] text-gray-800 antialiased selection:bg-amber-200 selection:text-gray-900">
    {{-- تطبيق الوضع الليلي قبل أول رسم لتجنّب الوميض عند تغيير الصفحات --}}
    <script>
    (function(){
        try {
            if (localStorage.getItem('clientPortalTheme') === 'dark') {
                document.body.classList.add('client-portal-dark');
            }
        } catch (e) {}
    })();
    </script>

    {{-- خلفية (الوضع الفاتح؛ الوضع الليلي يُطبّق عبر class على body) --}}
    <div class="fixed inset-0 -z-10 overflow-hidden portal-bg">
        <div class="absolute inset-0 bg-[#f5f6f8]"></div>
        <div class="absolute top-0 right-0 w-[60%] h-[70%] bg-amber-50/80 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[50%] h-[50%] bg-orange-50/60 blur-[80px] rounded-full"></div>
    </div>

    @yield('client_portal_body')

    @stack('scripts')
</body>
</html>
