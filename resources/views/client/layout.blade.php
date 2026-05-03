@extends('layouts.client_portal')

@section('title', 'بوابة العملاء - ONX')

@push('styles')
<style>
/* ─── Client Portal: خلفية فاتحة مريحة للعين ─── */
.client-portal-app { display: flex; flex-direction: column; min-height: 100vh; }
.client-portal-top { flex-shrink: 0; border-bottom: 1px solid #e5e7eb; background: rgba(255,255,255,.95); backdrop-blur: 12px; }
.client-portal-body { display: flex; flex: 1; min-height: 0; }
.client-portal-sidebar { width: 260px; flex-shrink: 0; border-left: 1px solid #e5e7eb; background: rgba(255,255,255,.9); overflow-y: auto; }
.client-portal-main { flex: 1; min-width: 0; overflow-y: auto; padding: 16px 20px; background: transparent; }
.portal-nav-link { border-radius: 14px; transition: all .2s ease; color: #4b5563; }
.portal-nav-link:hover { background: #f3f4f6; color: #1f2937; }
.portal-nav-link.active { background: rgba(245,158,11,.18); color: #92400e; border-inline-start: 3px solid #f59e0b; padding-inline-start: calc(0.75rem - 3px); }
.portal-avatar {
    width: 48px; height: 48px; border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    border: 2px solid #fcd34d;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; font-weight: 900; color: #b45309;
}
.portal-bottom-nav {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: rgba(255,255,255,.98);
    border-top: 1px solid #e5e7eb;
    padding: 6px 6px max(10px, env(safe-area-inset-bottom));
    z-index: 40;
    display: none;
}
@media (max-width: 1023px) {
    .portal-bottom-nav { display: flex; justify-content: space-around; align-items: center; }
    .client-portal-sidebar { display: none; }
    .client-portal-main { padding-bottom: 76px; }
}
@media (min-width: 1024px) {
    .portal-bottom-nav { display: none; }
}
.portal-bottom-link {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
    padding: 6px 4px; color: #6b7280;
    text-decoration: none; font-size: 10px; font-weight: 700; transition: all .2s;
    border-radius: 14px; margin: 0 2px;
}
.portal-bottom-link:hover { color: #92400e; background: rgba(245,158,11,.08); }
.portal-bottom-link.active { color: #92400e; background: rgba(245,158,11,.15); }
.portal-bottom-link.active::before { content: ''; display: block; width: 20px; height: 3px; background: #f59e0b; border-radius: 999px; position: absolute; top: 0; }
.portal-bottom-link i { font-size: 1.35rem; }

/* أنيميشن زر الوضع الليلي */
.portal-theme-toggle { transition: background 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s ease, box-shadow 0.3s ease; }
.portal-theme-toggle:hover { transform: scale(1.03); box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
.portal-theme-toggle:active { transform: scale(0.98); }
.portal-theme-toggle .thumb-circle { transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }
.portal-theme-toggle .thumb-circle.thumb-bounce { animation: thumb-bounce 0.4s ease-out; }
@keyframes thumb-bounce {
    0% { transform: scale(1); }
    45% { transform: scale(1.22); }
    70% { transform: scale(0.95); }
    100% { transform: scale(1); }
}
.portal-theme-toggle .thumb-circle { box-shadow: 0 2px 8px rgba(0,0,0,0.18); }
.portal-theme-toggle:hover .thumb-circle { box-shadow: 0 3px 12px rgba(0,0,0,0.22); }
.portal-theme-label { transition: opacity 0.25s ease, transform 0.25s ease; }

/* ═══ الوضع الليلي (ألوان كما كانت أول مرة) ═══ */
body.client-portal-dark { background: #0a0a0b !important; color: rgba(255,255,255,.88); }
body.client-portal-dark .portal-bg > div:first-child { background: linear-gradient(to bottom, #0c0f14, #08090b) !important; }
body.client-portal-dark .portal-bg .rounded-full { background: rgba(245,166,35,.06) !important; }
body.client-portal-dark .client-portal-app .client-portal-top { border-color: rgba(255,255,255,.08); background: rgba(10,10,11,.95); }
body.client-portal-dark .client-portal-app .client-portal-top span { color: #fff !important; }
body.client-portal-dark .client-portal-app .client-portal-top a { color: rgba(255,255,255,.6) !important; }
body.client-portal-dark .client-portal-app .client-portal-top a:hover { color: #fff !important; }
body.client-portal-dark .client-portal-app .client-portal-sidebar { border-color: rgba(255,255,255,.06); background: rgba(12,15,20,.9); }
body.client-portal-dark .client-portal-app .client-portal-sidebar .border-gray-200 { border-color: rgba(255,255,255,.1) !important; }
body.client-portal-dark .client-portal-app .client-portal-sidebar .text-gray-800 { color: #fff !important; }
body.client-portal-dark .client-portal-app .client-portal-sidebar .text-gray-500 { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .portal-nav-link { color: rgba(255,255,255,.7) !important; }
body.client-portal-dark .client-portal-app .portal-nav-link:hover { background: rgba(255,255,255,.06) !important; color: #fff !important; }
body.client-portal-dark .client-portal-app .portal-nav-link.active { background: rgba(245,166,35,.15) !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .portal-avatar { background: linear-gradient(135deg, rgba(245,166,35,.25), rgba(249,115,22,.2)) !important; border-color: rgba(245,166,35,.35) !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .portal-theme-toggle { background: linear-gradient(to left, rgba(245,158,11,.4), rgba(55,65,81,.8)) !important; }
body.client-portal-dark .client-portal-app .portal-theme-label { color: rgba(255,255,255,.8) !important; }
body.client-portal-dark .client-portal-app .border-red-200 { border-color: rgba(239,68,68,.3) !important; }
body.client-portal-dark .client-portal-app .bg-red-50 { background: rgba(239,68,68,.1) !important; }
body.client-portal-dark .client-portal-app .text-red-600 { color: #f87171 !important; }
body.client-portal-dark .client-portal-app .portal-bottom-nav { background: rgba(10,10,11,.98) !important; border-color: rgba(255,255,255,.08) !important; }
body.client-portal-dark .client-portal-app .portal-bottom-link { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .portal-bottom-link:hover,
body.client-portal-dark .client-portal-app .portal-bottom-link.active { color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .client-portal-main .rounded-2xl.border-amber-200 { border-color: rgba(245,166,35,.3) !important; background: rgba(245,166,35,.1) !important; color: #fcd34d !important; }
body.client-portal-dark .client-portal-app .client-portal-main .rounded-2xl.border-blue-200 { border-color: rgba(59,130,246,.3) !important; background: rgba(59,130,246,.1) !important; color: #93c5fd !important; }
body.client-portal-dark .client-portal-app .client-portal-main .rounded-2xl.border-red-200 { border-color: rgba(239,68,68,.3) !important; background: rgba(239,68,68,.1) !important; color: #fca5a5 !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-gray-800 { color: #fff !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-gray-700 { color: rgba(255,255,255,.9) !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-gray-600 { color: rgba(255,255,255,.7) !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-gray-500 { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-amber-600 { color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-green-600 { color: #4ade80 !important; }
body.client-portal-dark .client-portal-app .client-portal-main .text-red-600 { color: #f87171 !important; }
body.client-portal-dark .client-portal-app .hero-card { background: linear-gradient(145deg, #151b25 0%, #0c0f14 100%) !important; border-color: rgba(245,166,35,.2) !important; }
body.client-portal-dark .client-portal-app .hero-greeting { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .hero-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .hero-countdown { background: rgba(245,166,35,.15) !important; color: #fbbf24 !important; border-color: rgba(245,166,35,.25) !important; }
body.client-portal-dark .client-portal-app .hero-countdown-muted { background: rgba(255,255,255,.08) !important; color: rgba(255,255,255,.7) !important; border-color: rgba(255,255,255,.1) !important; }
body.client-portal-dark .client-portal-app .dash-steps .dash-step-line { background-color: rgba(255,255,255,.07); background: rgba(255,255,255,.07); }
body.client-portal-dark .client-portal-app .dash-steps .dash-step-line.done { background: #f59e0b !important; background-color: #f59e0b !important; }
body.client-portal-dark .client-portal-app .dash-steps .dash-step-line.active { background: #f59e0b !important; background-color: #f59e0b !important; }
body.client-portal-dark .client-portal-app .dash-step-circle { border-color: rgba(255,255,255,.1) !important; background: #1e2736 !important; color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .dash-step.done .dash-step-circle { background: #f59e0b !important; border-color: #f59e0b !important; color: #000 !important; }
body.client-portal-dark .client-portal-app .dash-step.active .dash-step-circle { border-color: #f59e0b !important; color: #fff !important; background: #f59e0b !important; }
body.client-portal-dark .client-portal-app .dash-step-label { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .dash-step.done .dash-step-label,
body.client-portal-dark .client-portal-app .dash-step.active .dash-step-label { color: #fff !important; }
body.client-portal-dark .client-portal-app .stat-card { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .stat-label { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .alert-new { background: rgba(34,197,94,.1) !important; border-color: rgba(34,197,94,.2) !important; color: #4ade80 !important; }
body.client-portal-dark .client-portal-app .msg-preview { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .empty-state { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .empty-state .font-bold { color: rgba(255,255,255,.8) !important; }
body.client-portal-dark .client-portal-app a.block.rounded-2xl.border-gray-200 { border-color: rgba(255,255,255,.1) !important; background: rgba(255,255,255,.05) !important; }
body.client-portal-dark .client-portal-app a.block.rounded-2xl.border-gray-200:hover { border-color: rgba(245,166,35,.3) !important; }
body.client-portal-dark .client-portal-app .booking-card { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .booking-card:hover { border-color: rgba(245,166,35,.25) !important; }
body.client-portal-dark .client-portal-app .booking-card-id { color: #fff !important; }
body.client-portal-dark .client-portal-app .booking-card-meta { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .empty-bookings { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .panel-pay { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .panel-pay-head a { color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .pay-bar-wrap { background: #1e2736 !important; }
body.client-portal-dark .client-portal-app .pay-row { background: #1e2736 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .pay-row-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .pay-row-meta { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .empty-state-portal { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .file-card { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .file-card:hover { border-color: rgba(245,166,35,.3) !important; }
body.client-portal-dark .client-portal-app .file-card-name { color: #fff !important; }
body.client-portal-dark .client-portal-app .file-card-meta { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .border-gray-200.bg-white { border-color: rgba(255,255,255,.1) !important; background: rgba(21,27,37,.8) !important; }
body.client-portal-dark .client-portal-app .border-gray-200.bg-white .text-gray-800 { color: #fff !important; }
body.client-portal-dark .client-portal-app .border-gray-200.bg-white .text-gray-700 { color: rgba(255,255,255,.9) !important; }
body.client-portal-dark .client-portal-app .border-gray-200.bg-white label { color: rgba(255,255,255,.8) !important; }
body.client-portal-dark .client-portal-app .border-gray-200.bg-white input,
body.client-portal-dark .client-portal-app .border-gray-200.bg-white textarea,
body.client-portal-dark .client-portal-app .border-gray-200.bg-white select { background: rgba(255,255,255,.05) !important; border-color: rgba(255,255,255,.1) !important; color: #fff !important; }
body.client-portal-dark .client-portal-app .portal-wrap { background: transparent !important; color: rgba(255,255,255,.88) !important; }
body.client-portal-dark .client-portal-app .panel { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .panel-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .progress-track { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .info-item { background: #1e2736 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .info-value { color: #fff !important; }
body.client-portal-dark .client-portal-app .payment-row { background: #1e2736 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .payment-row-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .pay-amount-box { background: #1e2736 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .pay-bar-wrap { background: #1e2736 !important; }
body.client-portal-dark .client-portal-app .file-row { background: #1e2736 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .file-row .file-name { color: #fff !important; }
body.client-portal-dark .client-portal-app .file-row .file-meta { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .invoice-btn { background: rgba(245,166,35,.1) !important; border-color: rgba(245,166,35,.25) !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .portal-alert-success { background: rgba(34,197,94,.1) !important; border-color: rgba(34,197,94,.2) !important; color: #4ade80 !important; }
body.client-portal-dark .client-portal-app .portal-alert-danger { background: rgba(239,68,68,.1) !important; border-color: rgba(239,68,68,.2) !important; color: #f87171 !important; }
body.client-portal-dark .client-portal-app .portal-header { border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .portal-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .portal-subtitle { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .portal-back { color: rgba(255,255,255,.42) !important; }
body.client-portal-dark .client-portal-app .portal-back:hover { color: #fbbf24 !important; }
/* صور مشروعي — الوضع الليلي */
body.client-portal-dark .client-portal-app .portal-selected-count { border-color: rgba(245,166,35,.25) !important; background: rgba(245,166,35,.12) !important; color: #fcd34d !important; }
body.client-portal-dark .client-portal-app a.flex.rounded-2xl.border-gray-200.bg-white { border-color: rgba(255,255,255,.1) !important; background: rgba(255,255,255,.05) !important; }
body.client-portal-dark .client-portal-app a.flex.rounded-2xl.border-gray-200.bg-white:hover { border-color: rgba(245,166,35,.3) !important; }
body.client-portal-dark .client-portal-app a.flex.rounded-2xl.border-gray-200.bg-white .text-gray-800 { color: #fff !important; }
body.client-portal-dark .client-portal-app a.flex.rounded-2xl.border-gray-200.bg-white .text-gray-500 { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app a.flex.rounded-2xl.border-gray-200.bg-white .text-gray-600 { color: rgba(255,255,255,.7) !important; }
body.client-portal-dark .client-portal-app .grid .rounded-2xl.border-gray-200.bg-white { border-color: rgba(255,255,255,.1) !important; background: rgba(21,27,37,.6) !important; }
/* صور الحجز — القلب وزر التحْميل أوضح في الوضع الليلي */
body.client-portal-dark .client-portal-app .photo-fav { background: rgba(0,0,0,.5) !important; }
body.client-portal-dark .client-portal-app .photo-fav span.text-white\/60 { color: rgba(255,255,255,.85) !important; }
body.client-portal-dark .client-portal-app .photo-fav:hover span.text-white\/60 { color: #fcd34d !important; }
body.client-portal-dark .client-portal-app .photo-fav .text-red-400 { color: #f87171 !important; filter: drop-shadow(0 0 6px rgba(248,113,113,.4)); }
body.client-portal-dark .client-portal-app a[download].rounded-full.bg-black\/60 { background: rgba(0,0,0,.55) !important; color: #fcd34d !important; }
body.client-portal-dark .client-portal-app a[download].rounded-full.bg-black\/60:hover { background: rgba(245,158,11,.5) !important; color: #fff !important; }
/* عناوين صفحة صور الحجز في الوضع الليلي */
body.client-portal-dark .client-portal-app .project-photos-page h2 { color: #fff !important; }
body.client-portal-dark .client-portal-app .project-photos-page .text-gray-600 { color: rgba(255,255,255,.75) !important; }
body.client-portal-dark .client-portal-app .project-photos-page a.text-amber-600 { color: #fbbf24 !important; }
/* الميديا — الوضع الليلي */
body.client-portal-dark .client-portal-app .media-filter a { background: #1e2736 !important; border-color: rgba(255,255,255,.1) !important; color: rgba(255,255,255,.7) !important; }
body.client-portal-dark .client-portal-app .media-filter a:hover { border-color: rgba(245,166,35,.4) !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .media-filter a.active { background: rgba(245,166,35,.15) !important; border-color: #f59e0b !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .media-section-title { color: #fff !important; }
body.client-portal-dark .client-portal-app .media-gallery .thumb { border-color: rgba(255,255,255,.1) !important; background: #1e2736 !important; }
body.client-portal-dark .client-portal-app .video-card { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .video-card .info .label { color: #fff !important; }
body.client-portal-dark .client-portal-app .video-card .info .meta { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .file-dl-row { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
body.client-portal-dark .client-portal-app .file-dl-row .name { color: #fff !important; }
body.client-portal-dark .client-portal-app .file-dl-row .meta { color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .empty-media { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; color: rgba(255,255,255,.5) !important; }
body.client-portal-dark .client-portal-app .media-files-by-order .order-head { color: #fff !important; border-color: rgba(245,166,35,.3) !important; }
body.client-portal-dark .client-portal-app .media-files-by-order .order-num { background: rgba(245,166,35,.15) !important; color: #fbbf24 !important; }
body.client-portal-dark .client-portal-app .media-lightbox-wrap #media-lightbox-close,
body.client-portal-dark .client-portal-app .media-lightbox-wrap #media-lightbox-prev,
body.client-portal-dark .client-portal-app .media-lightbox-wrap #media-lightbox-next { background: rgba(255,255,255,.2) !important; color: #fff !important; }
body.client-portal-dark .client-portal-app .media-lightbox-wrap #media-lightbox-download { color: #000 !important; }
/* شارات نوع الحساب — الوضع الليلي */
body.client-portal-dark .client-portal-app .badge-company { background: rgba(34,197,94,.12) !important; color: #4ade80 !important; border-color: rgba(34,197,94,.25) !important; }
body.client-portal-dark .client-portal-app .badge-individual { background: rgba(255,255,255,.08) !important; color: rgba(255,255,255,.6) !important; border-color: rgba(255,255,255,.12) !important; }
/* تحسين active nav في الوضع الليلي */
body.client-portal-dark .client-portal-app .portal-nav-link.active { background: rgba(245,166,35,.18) !important; color: #fbbf24 !important; border-inline-start-color: #f59e0b !important; }
/* bottom nav active في الوضع الليلي */
body.client-portal-dark .client-portal-app .portal-bottom-link.active { background: rgba(245,166,35,.15) !important; }
body.client-portal-dark .client-portal-app .portal-bottom-link.active::before { background: #f59e0b !important; }
</style>
@endpush

@section('client_portal_body')
<div class="client-portal-app"
     x-data="{ dark: localStorage.getItem('clientPortalTheme') === 'dark' }"
     x-init="
       document.body.classList.toggle('client-portal-dark', dark);
       $el.classList.toggle('client-portal-dark', dark);
       $watch('dark', v => {
         document.body.classList.toggle('client-portal-dark', v);
         $el.classList.toggle('client-portal-dark', v);
         localStorage.setItem('clientPortalTheme', v ? 'dark' : 'light');
       });
     "
     :class="{ 'client-portal-dark': dark }">
    {{-- شريط علوي بسيط (مثل الأدمن): شعار + عنوان فقط --}}
    <header class="client-portal-top">
        <div class="flex h-14 items-center justify-between px-4 lg:px-6">
            <div class="flex items-center gap-2">
                <span class="text-xl font-black tracking-widest text-gray-800">ONX</span>
                <span class="h-2 w-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.4)]"></span>
                <span class="mr-2 hidden text-sm font-bold text-gray-500 sm:inline">بوابة العملاء</span>
            </div>
            <a href="/" class="text-xs font-bold text-gray-500 transition hover:text-gray-800">الموقع الرئيسي ←</a>
        </div>
    </header>

    <div class="client-portal-body">
        {{-- Sidebar (Desktop) --}}
        <aside class="client-portal-sidebar">
            <div class="p-4">
                <div class="mb-4 flex items-start gap-3 border-b border-gray-200 pb-4">
                    <div class="portal-avatar flex-shrink-0">
                        @if(!empty($client->is_company) && !empty($client->business_name))
                            {{ mb_substr($client->business_name, 0, 1) }}
                        @else
                            {{ mb_substr($client->name ?? '؟', 0, 1) }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        @if(!empty($client->is_company) && !empty($client->business_name))
                            <p class="truncate font-black text-gray-800 leading-tight">{{ $client->business_name }}</p>
                            <p class="truncate text-xs text-gray-500 mt-0.5">{{ $client->name }}</p>
                        @else
                            <p class="truncate font-black text-gray-800">{{ $client->name ?? 'العميل' }}</p>
                            @if(isset($activeBooking) && $activeBooking && $activeBooking->event_date)
                                <p class="text-xs text-gray-500 mt-0.5">
                                    @if($activeBooking->event_date->isFuture())
                                        حفلتك بعد {{ $activeBooking->event_date->diffInDays(now()) }} يوم
                                    @else
                                        تمت الحفلة
                                    @endif
                                </p>
                            @else
                                <p class="text-xs text-gray-500 mt-0.5">مساحتك الخاصة</p>
                            @endif
                        @endif
                        {{-- شارة نوع الحساب --}}
                        <div class="mt-1.5">
                            @if(!empty($client->is_company))
                                <span class="badge-company"><i class="bi bi-building"></i> شركة</span>
                            @else
                                <span class="badge-individual"><i class="bi bi-person"></i> فرد</span>
                            @endif
                        </div>
                    </div>
                </div>
                <nav class="space-y-1">
                    <a href="{{ route('client.dashboard') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.dashboard') ? 'active' : 'text-gray-500' }}">
                        <span>🏠</span> الرئيسية
                    </a>
                    <a href="{{ route('client.bookings') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.bookings*') && !request()->routeIs('client.review*') ? 'active' : 'text-gray-500' }}">
                        <span>📋</span> حجوزاتي
                    </a>
                    <a href="{{ route('client.payments') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.payments*') ? 'active' : 'text-gray-500' }}">
                        <span>💰</span> المدفوعات
                    </a>
                    <a href="{{ route('client.subscriptions') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.subscriptions*') ? 'active' : 'text-gray-500' }}">
                        <span>📢</span> الاشتراكات
                    </a>
                    <a href="{{ route('client.media') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.media*') || request()->routeIs('client.project-photos*') || request()->routeIs('client.files*') ? 'active' : 'text-gray-500' }}">
                        <span>🎬</span> الميديا
                    </a>
                    <a href="{{ route('client.messages') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.messages*') ? 'active' : 'text-gray-500' }}">
                        <span>✉️</span> رسائلي
                        @if(isset($unreadMessages) && $unreadMessages > 0)
                            <span class="mr-auto rounded-full bg-red-500/90 px-1.5 py-0.5 text-[10px] font-black text-white">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    @if(isset($canReview) && $canReview)
                    <a href="{{ route('client.review.create') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.review*') ? 'active' : 'text-gray-500' }}">
                        <span>⭐</span> أضف رأيك
                    </a>
                    @endif
                    <a href="{{ route('client.profile') }}" class="portal-nav-link flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold {{ request()->routeIs('client.profile*') ? 'active' : 'text-gray-500' }}">
                        <span>👤</span> الملف الشخصي
                    </a>
                </nav>
                <form action="{{ route('client.logout') }}" method="POST" class="mt-4 border-t border-gray-200 pt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-100">تسجيل الخروج</button>
                </form>
                <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between gap-3">
                    <span class="text-sm font-bold text-gray-600 portal-theme-label" x-text="dark ? 'الوضع النهاري' : 'الوضع الليلي'"></span>
                    <button type="button"
                            @click="dark = !dark; $refs.thumb.classList.add('thumb-bounce'); setTimeout(() => $refs.thumb.classList.remove('thumb-bounce'), 420)"
                            class="portal-theme-toggle flex-shrink-0 rounded-full p-0.5 w-12 h-7 flex items-center focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:ring-offset-2 focus:ring-offset-transparent"
                            :class="dark ? 'bg-gradient-to-l from-amber-600 to-gray-700 justify-end' : 'bg-gradient-to-r from-amber-200 to-sky-300 justify-start'"
                            aria-label="تبديل الوضع الليلي">
                        <span x-ref="thumb" class="thumb-circle w-5 h-5 rounded-full bg-white flex-shrink-0"></span>
                    </button>
                </div>
            </div>
        </aside>

        <main class="client-portal-main">
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-bold text-amber-800">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm font-bold text-blue-800">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('client_content')
        </main>
    </div>
</div>

{{-- Bottom Nav (Mobile) --}}
<nav class="portal-bottom-nav" aria-label="تنقل العملاء">
    <a href="{{ route('client.dashboard') }}" class="portal-bottom-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i> الرئيسية
    </a>
    <a href="{{ route('client.bookings') }}" class="portal-bottom-link {{ request()->routeIs('client.bookings*') && !request()->routeIs('client.project-photos*') && !request()->routeIs('client.review*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> حجوزاتي
    </a>
    <a href="{{ route('client.payments') }}" class="portal-bottom-link {{ request()->routeIs('client.payments*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> المدفوعات
    </a>
    <a href="{{ route('client.subscriptions') }}" class="portal-bottom-link {{ request()->routeIs('client.subscriptions*') ? 'active' : '' }}">
        <i class="bi bi-arrow-repeat"></i> الاشتراكات
    </a>
    <a href="{{ route('client.media') }}" class="portal-bottom-link {{ request()->routeIs('client.media*') || request()->routeIs('client.project-photos*') || request()->routeIs('client.files*') ? 'active' : '' }}">
        <i class="bi bi-camera-video"></i> الميديا
    </a>
    <a href="{{ route('client.messages') }}" class="portal-bottom-link {{ request()->routeIs('client.messages*') ? 'active' : '' }}">
        <span class="relative inline-block">
            <i class="bi bi-chat-dots-fill"></i>
            @if(isset($unreadMessages) && $unreadMessages > 0)
                <span class="absolute -top-1 -left-1 h-4 min-w-[16px] rounded-full bg-red-500 px-1 text-[9px] font-black text-white leading-4 flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </span>
        رسائلي
    </a>
</nav>
@endsection
