@extends('layouts.front_tailwind')

@section('title', 'خدماتنا — ONX')
@section('has_hero', true)

@push('styles')
<style>
/* ══ ONX EDGE — SERVICES PAGE v3 ════════════════════════════════════ */

/* Glitch — same as homepage */
@keyframes glitch1 {
    0%, 90%, 100% { clip-path: none; transform: translate(0); }
    92%            { clip-path: polygon(0 30%, 100% 30%, 100% 50%, 0 50%); transform: translate(-3px, 0); }
    94%            { clip-path: polygon(0 65%, 100% 65%, 100% 80%, 0 80%); transform: translate(3px, 0); }
    96%            { clip-path: polygon(0 10%, 100% 10%, 100% 25%, 0 25%); transform: translate(-2px, 0); }
}
.glitch { animation: glitch1 8s ease-in-out infinite; }

/* Ticker */
@keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.ticker-inner { animation: ticker 22s linear infinite; }
.ticker-inner:hover { animation-play-state: paused; }

/* Shimmer border — same as homepage */
@keyframes borderSpin { to { --border-angle: 360deg; } }
@property --border-angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }
.border-spin {
    --border-angle: 0deg;
    animation: borderSpin 6s linear infinite;
    background:
        linear-gradient(#0a0a0a, #0a0a0a) padding-box,
        conic-gradient(from var(--border-angle), #c8490a 0%, transparent 30%, transparent 70%, #c8490a 100%) border-box;
    border: 1px solid transparent;
}

/* Reveal on scroll */
[data-reveal] .reveal-item,
[data-reveal] .reveal-card {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
}
[data-reveal].in-view .reveal-item,
[data-reveal].in-view .reveal-card { opacity: 1; transform: none; }
.reveal-delay-1 { transition-delay: .05s !important; }
.reveal-delay-2 { transition-delay: .12s !important; }
.reveal-delay-3 { transition-delay: .20s !important; }
.reveal-delay-4 { transition-delay: .28s !important; }
.reveal-delay-5 { transition-delay: .36s !important; }
.reveal-delay-6 { transition-delay: .44s !important; }
.reveal-delay-7 { transition-delay: .52s !important; }

/* Card hover glow */
.glow-hover { transition: box-shadow .3s ease, transform .3s ease, border-color .3s ease; }
.glow-hover:hover { box-shadow: 0 0 40px rgba(200,73,10,.18), 0 24px 60px rgba(0,0,0,.5); }

/* Grain texture */
.grain::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    opacity: .022;
    pointer-events: none;
    z-index: 0;
    border-radius: inherit;
}

/* Reel strip — same as homepage */
.reel-strip {
    background: repeating-linear-gradient(
        90deg,
        transparent 0px, transparent 18px,
        rgba(255,255,255,.06) 18px, rgba(255,255,255,.06) 20px
    );
}

/* Stat highlight */
.stat-highlight {
    background: linear-gradient(135deg, var(--onx-brand, #ff6b1a) 0%, rgba(var(--onx-brand-rgb,232,124,42),.7) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}

/* Dynamic brand utilities */
.onx-brand-text  { color: var(--onx-brand, #E87C2A); }
.onx-brand-bg-soft { background: rgba(var(--onx-brand-rgb,232,124,42),.08); }
.onx-brand-bg    { background: var(--onx-brand, #E87C2A); }
.onx-brand-border { border-color: rgba(var(--onx-brand-rgb,232,124,42),.3); }

/* Category tab strip */
.cat-tab {
    position: relative;
    padding: 10px 18px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    color: rgba(255,255,255,.45);
    border: 1px solid rgba(255,255,255,.08);
    background: rgba(255,255,255,.03);
    cursor: pointer;
    transition: all .25s ease;
    white-space: nowrap;
}
.cat-tab:hover  { color: rgba(255,255,255,.75); border-color: rgba(255,255,255,.18); }
.cat-tab.active { color: #fff; border-color: transparent; }

/* Services band */
.onx-services-band {
    background-color: #080808;
    background-image:
        radial-gradient(ellipse 78% 62% at 50% 14%, var(--onx-tint, rgba(232,124,42,.22)) 0%, transparent 58%),
        radial-gradient(ellipse 90% 70% at 80% 90%, var(--onx-tint-edge, rgba(232,124,42,.06)) 0%, transparent 45%);
    transition: background-color .5s ease;
}

/* Blob */
@keyframes onx-breathe{0%,100%{transform:scale(.88);opacity:.58}50%{transform:scale(1.14);opacity:.82}}
.onx-blob{position:absolute;border-radius:50%;pointer-events:none;filter:blur(68px);transition:background .65s ease}
.onx-b1{width:60%;height:120%;top:-10%;right:-6%;animation:onx-breathe 5s ease-in-out infinite}
.onx-b2{width:46%;height:100%;bottom:-12%;left:-5%;animation:onx-breathe 6.5s ease-in-out infinite 2.2s}
.onx-b3{width:30%;height:62%;top:30%;left:33%;animation:onx-breathe 4.8s ease-in-out infinite 1s}

/* Service card icon */
.onx-icon-c{width:52px;height:52px;border-radius:50%;background:rgba(var(--onx-brand-rgb,232,124,42),.1);border:1.5px solid rgba(var(--onx-brand-rgb,232,124,42),.2);display:flex;align-items:center;justify-content:center;transition:all .3s ease}
.group:hover .onx-icon-c{background:rgba(var(--onx-brand-rgb,232,124,42),.2);border-color:rgba(var(--onx-brand-rgb,232,124,42),.45)}
</style>
@endpush

@section('content')

@php
    $hc = (int) ($stats['happy_clients'] ?? 0);
    $hcLabel = $hc >= 1000
        ? (rtrim(rtrim(number_format($hc / 1000, 1, '.', ''), '0'), '.') . 'k+')
        : (string) max(1, $hc);
    $categoriesJson = json_encode(
        $categoriesPayload,
        JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP
        | JSON_INVALID_UTF8_SUBSTITUTE
    ) ?: '[]';
@endphp

<div
    class="text-white"
    x-data="onxServicesPage()"
    :style="'direction:rtl;--onx-brand:' + activeCat().bg_color + ';--onx-brand-rgb:' + hexToRgb(activeCat().bg_color)"
    @keydown.window="onViewerKeydown($event)"
>
    <script type="application/json" id="onx-services-categories-payload" class="hidden">{!! $categoriesJson !!}</script>

    {{-- ══ ① HERO — identical layout to homepage ═════════════════════════ --}}
    <section class="relative isolate min-h-[88vh] overflow-hidden flex items-center grain" data-reveal>

        {{-- Background image --}}
        <div class="absolute inset-0 -z-20">
            <img src="{{ asset('img/front/service/servicehero.png') }}" alt="" aria-hidden="true"
                 class="h-full w-full object-cover opacity-[0.16]">
        </div>

        {{-- Gradient overlays — dynamic category color --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#080808] via-[#050505]/90 to-[#020202]"></div>
        <div class="absolute inset-0 -z-10" :style="'background:radial-gradient(ellipse 80% 60% at 60% -10%,'+activeCat().bg_color+'28,transparent)'"></div>
        <div class="absolute inset-0 -z-10" :style="'background:radial-gradient(circle at 20% 80%,'+activeCat().bg_color+'10,transparent 35%)'"></div>

        {{-- Decorative vertical lines --}}
        <div class="absolute inset-y-0 left-0 right-0 -z-10 opacity-[0.04]">
            <div class="mx-auto h-full max-w-7xl px-6 lg:px-8">
                <div class="flex h-full justify-between">
                    @foreach(range(1,5) as $i)
                    <div class="w-px bg-white"></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mx-auto w-full max-w-7xl px-6 pt-28 pb-12 lg:px-8 lg:pt-32 lg:pb-16">
            <div class="grid gap-10 lg:grid-cols-2 lg:gap-14 items-center">

                {{-- LEFT — Copy (order-2 on mobile, order-1 on desktop) --}}
                <div class="order-2 lg:order-1">

                    {{-- Tag --}}
                    <div class="reveal-item reveal-delay-1 mb-6 inline-flex items-center gap-2.5 rounded-full px-4 py-2 text-[11px] font-black uppercase tracking-[.22em] backdrop-blur"
                         :style="'border:1px solid ' + activeCat().bg_color + '40;background:' + activeCat().bg_color + '14;color:' + activeCat().bg_color">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60" :style="'background:' + activeCat().bg_color"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full" :style="'background:' + activeCat().bg_color"></span>
                        </span>
                        ONX — الخدمات
                    </div>

                    {{-- H1 --}}
                    <h1 class="reveal-item reveal-delay-2 glitch text-[clamp(1.6rem,3vw,2.4rem)] font-black leading-[1.18] tracking-tight text-white">
                        ماذا تحتاج <span class="onx-brand-text">اليوم</span>؟
                    </h1>
                    <p class="reveal-item reveal-delay-2 mt-2 text-[clamp(.9rem,1.6vw,1.2rem)] font-extrabold text-white/40 tracking-tight">
                        اختر التصنيف — التفاصيل والباقات من صفحة كل خدمة.
                    </p>

                    <p class="reveal-item reveal-delay-3 mt-5 max-w-xl text-sm leading-8 text-white/55">
                        شركة إنتاج بصري متخصصة في الإعلانات، الحفلات، والتغطيات الراقية.
                        نحوّل الفكرة إلى صورة ذات حضور قوي — بتفاصيل دقيقة وإيقاع سينمائي.
                    </p>

                    {{-- CTAs --}}
                    <div class="reveal-item reveal-delay-4 mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('booking') }}"
                           class="group relative inline-flex items-center gap-2 overflow-hidden rounded-full px-7 py-3.5 text-[13px] font-black text-black transition duration-300 hover:-translate-y-0.5 active:scale-[.98]"
                           :style="'background:' + activeCat().bg_color + ';box-shadow:0 0 30px ' + activeCat().bg_color + '30'">
                            <span>احجز مشروعك الآن</span>
                            <svg class="h-4 w-4 transition duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-7 py-3.5 text-[13px] font-extrabold text-white/80 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:text-white active:scale-[.98]"
                           @mouseenter="$el.style.borderColor=activeCat().bg_color+'66';$el.style.background=activeCat().bg_color+'14'"
                           @mouseleave="$el.style.borderColor='rgba(255,255,255,.12)';$el.style.background='rgba(255,255,255,.05)'">
                            تواصل معنا
                        </a>
                    </div>

                    {{-- CATEGORIES — same style as homepage feature chips --}}
                    <div class="reveal-item reveal-delay-5 mt-10" id="onx-categories">
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($categoriesPayload as $i => $cat)
                                @php $catColor = $cat['bg_color'] ?? '#E87C2A'; @endphp
                                <button
                                    type="button"
                                    @click="select({{ $i }})"
                                    :class="idx === {{ $i }}
                                        ? 'border-opacity-40 -translate-y-0.5'
                                        : 'border-white/8 bg-white/[.04] hover:border-white/20 hover:-translate-y-0.5'"
                                    :style="idx === {{ $i }}
                                        ? 'border-color:{{ $catColor }}55;background:{{ $catColor }}12;box-shadow:0 0 28px {{ $catColor }}18'
                                        : ''"
                                    class="glow-hover rounded-2xl border p-3.5 text-right transition duration-300 backdrop-blur"
                                    @mouseenter="if(idx !== {{ $i }}) { $el.style.borderColor='{{ $catColor }}33' }"
                                    @mouseleave="if(idx !== {{ $i }}) { $el.style.borderColor='rgba(255,255,255,.08)' }"
                                >
                                    <div class="mb-2 text-xl">{{ $cat['icon'] ?? '📌' }}</div>
                                    <div class="text-[12px] font-black text-white">{{ $cat['name'] }}</div>
                                    @if(!empty($cat['description']))
                                        <div class="mt-1 text-[10px] leading-5 text-white/50 line-clamp-2">{{ $cat['description'] }}</div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIGHT — Visual card (same as homepage) --}}
                <div class="reveal-card reveal-delay-3 order-1 lg:order-2">
                    <div class="relative mx-auto max-w-md">

                        {{-- Glow halo --}}
                        <div class="absolute -inset-10 rounded-[50%] blur-3xl transition-all duration-700"
                             :style="'background:' + activeCat().bg_color + '15'"></div>

                        {{-- Main image card — shimmer border --}}
                        <div class="border-spin relative overflow-hidden rounded-[28px] shadow-[0_32px_90px_rgba(0,0,0,.6)]">
                            <img src="{{ asset('img/front/service/servicehero.png') }}" alt="ONX خدمات" class="h-[440px] w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

                            {{-- Top badges --}}
                            <div class="absolute left-0 right-0 top-0 flex items-center justify-between p-4">
                                <span class="rounded-full border border-white/10 bg-black/40 px-3 py-1 text-[10px] font-black tracking-[.2em] text-white/60 backdrop-blur">
                                    ONX FRAME
                                </span>
                                <span class="rounded-full border px-3 py-1 text-[10px] font-black tracking-wide backdrop-blur"
                                      :style="'border-color:' + activeCat().bg_color + '55;background:' + activeCat().bg_color + '20;color:' + activeCat().bg_color">
                                    ● <span x-text="activeCat().name || 'SERVICES'"></span>
                                </span>
                            </div>

                            {{-- Bottom caption --}}
                            <div class="absolute bottom-0 left-0 right-0 p-5">
                                <p class="text-[10px] font-black uppercase tracking-[.2em] onx-brand-text">خدمات ONX</p>
                                <h2 class="mt-1.5 text-xl font-black text-white" x-text="activeCat().name ? 'تصنيف: ' + activeCat().name : 'صورة تُحَس قبل أن تُشاهَد'"></h2>
                                <p class="mt-1.5 text-xs leading-6 text-white/60"
                                   x-text="activeCat().description || 'من أول frame لآخر لقطة — كل شيء مدروس.'"></p>
                            </div>
                        </div>

                        

                        
                    </div>
                </div>

            </div>
        </div>

        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-[#050505] to-transparent"></div>
    </section>

    {{-- ══ TICKER ═════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden border-y border-white/8 bg-[#080808] py-3.5">
        <div class="reel-strip absolute inset-0 opacity-50"></div>
        <div class="ticker-inner flex items-center gap-0 whitespace-nowrap relative z-10">
            @php
            $tickers = [];
            foreach($categoriesPayload as $cat) {
                $tickers[] = $cat['name'];
                $tickers[] = '●';
                foreach(($cat['services'] ?? []) as $svc) {
                    $tickers[] = $svc['name'] ?? '';
                    $tickers[] = '●';
                }
            }
            if (empty($tickers)) {
                $tickers = ['إعلانات تجارية','●','تغطية فعاليات','●','إنتاج سينمائي','●','هوية بصرية','●','تصوير احترافي','●'];
            }
            $tickers = array_merge($tickers, $tickers);
            @endphp
            @foreach($tickers as $item)
                <span class="px-5 text-[11px] font-black uppercase tracking-[.2em] {{ $item === '●' ? 'onx-brand-text' : 'text-white/35' }}">{{ $item }}</span>
            @endforeach
        </div>
    </div>

    {{-- ══ ② SERVICES GRID ═══════════════════════════════════════════════ --}}
    <section
        class="onx-services-band relative overflow-hidden border-b border-white/8 py-16 lg:py-20 grain"
        :style="{ '--onx-tint': activeCat().tint_soft || 'rgba(232,124,42,0.22)', '--onx-tint-edge': activeCat().tint_edge || 'rgba(232,124,42,0.08)' }"
        data-reveal
    >
        <div class="onx-blob onx-b1" :style="'background:' + blobGrad(activeCat().blob1)"></div>
        <div class="onx-blob onx-b2" :style="'background:' + blobGrad(activeCat().blob2)"></div>
        <div class="onx-blob onx-b3" :style="'background:' + blobGrad(activeCat().blob3)"></div>

        <div class="relative z-[2] mx-auto max-w-6xl px-6 lg:px-8">
            {{-- Section header --}}
            <div class="reveal-item reveal-delay-1 mb-10 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.22em] onx-brand-text" style="opacity:.75">خدمات</p>
                    <h2 class="mt-1 text-xl font-black text-white lg:text-2xl" x-text="activeCat().name || '—'"></h2>
                </div>
                <p class="text-xs text-white/35">
                    <span x-text="(activeCat().services || []).length"></span> خدمات في هذا التصنيف
                </p>
            </div>

            <template x-if="!cats.length">
                <p class="py-12 text-center text-sm text-white/40">لا توجد بيانات تصنيفات.</p>
            </template>
            <template x-if="cats.length && (!activeCat().services || activeCat().services.length === 0)">
                <p class="py-12 text-center text-sm text-white/50">لا توجد خدمات مرتبطة بهذا التصنيف بعد.</p>
            </template>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                 x-show="activeCat().services && activeCat().services.length">
                <template x-for="svc in (activeCat().services || [])" :key="svc.id">
                    <div class="group glow-hover rounded-[22px] border border-white/8 bg-white/[0.04] p-5 pt-8 text-center backdrop-blur transition duration-300 hover:-translate-y-1.5 hover:bg-white/[0.07]"
                         @mouseenter="$el.style.borderColor=activeCat().bg_color+'44'"
                         @mouseleave="$el.style.borderColor='rgba(255,255,255,.08)'">
                        <div class="flex justify-center">
                            <div class="onx-icon-c">
                                <span class="text-[22px]" x-text="svc.icon"></span>
                            </div>
                        </div>
                        <div class="mt-5 flex flex-col gap-2">
                            <div class="text-sm font-bold text-white" x-text="svc.name"></div>
                            <p class="min-h-[3rem] text-[11px] leading-relaxed text-white/45" x-text="svc.desc"></p>
                            <template x-if="svc.contact_only">
                                <span class="mx-auto inline-block rounded-full border border-violet-400/30 bg-violet-500/10 px-2.5 py-1 text-[9px] font-bold text-violet-200">تواصل مباشر</span>
                            </template>
                            <a :href="svc.route"
                               class="mt-3 inline-flex w-full items-center justify-center rounded-full border bg-white/5 py-2.5 text-[11px] font-extrabold text-white/80 transition duration-300 hover:text-white"
                               :style="'border-color:rgba(var(--onx-brand-rgb),.18)'"
                               @mouseenter="$el.style.borderColor=activeCat().bg_color+'66';$el.style.background=activeCat().bg_color+'14'"
                               @mouseleave="$el.style.borderColor='rgba(var(--onx-brand-rgb),.18)';$el.style.background='rgba(255,255,255,.05)'">
                                تفاصيل الخدمة
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ══ ③ PORTFOLIO ════════════════════════════════════════════════════ --}}
    <section class="relative py-16 lg:py-20" data-reveal>
        <div class="mx-auto max-w-6xl px-6 lg:px-8">
            <div class="reveal-item reveal-delay-1 mb-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[.22em] onx-brand-text" style="opacity:.75">portfolio</p>
                    <h2 class="mt-1 text-lg font-black text-white">أعمال مختارة</h2>
                </div>
                <span class="rounded-full px-3 py-1.5 text-[10px] font-bold"
                      :style="'border:1px solid ' + activeCat().bg_color + '44;background:' + activeCat().bg_color + '14;color:' + activeCat().bg_color"
                      x-text="activeCat().name || ''"></span>
            </div>

            <template x-if="cats.length && (!(activeCat().portfolio || []).length)">
                <p class="py-10 text-center text-sm text-white/45">
                    لا توجد أعمال في هذا التصنيف بعد.
                </p>
            </template>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3" x-show="(activeCat().portfolio || []).length">
                <template x-for="(item, pi) in (activeCat().portfolio || [])" :key="(activeCat().slug || 'c') + '-' + (item.id != null ? item.id : pi)">
                    <button
                        type="button"
                        class="reveal-card group relative h-52 w-full cursor-zoom-in overflow-hidden rounded-[20px] border border-white/8 bg-white/[0.03] text-start ring-offset-2 ring-offset-[#050505] transition duration-300 hover:-translate-y-1 hover:border-white/15 hover:shadow-[0_20px_60px_rgba(0,0,0,.4)] focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 sm:h-56"
                        @click="openViewer(pi)"
                    >
                        <template x-if="item.media_type === 'youtube'">
                            <span class="relative block h-full w-full">
                                <img :src="item.thumb_url" :alt="item.title || ''" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                                <span class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/25 transition group-hover:bg-black/35">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-full border-2 border-white/80 bg-black/50 text-2xl text-white shadow-lg backdrop-blur-sm transition group-hover:scale-110 group-hover:border-orange-500/60">▶</span>
                                </span>
                            </span>
                        </template>
                        <template x-if="item.media_type === 'video'">
                            <span class="relative block h-full w-full bg-black/40">
                                <template x-if="item.poster_url">
                                    <img :src="item.poster_url" :alt="item.title || ''" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                                </template>
                                <template x-if="!item.poster_url">
                                    <video class="h-full w-full object-cover opacity-90 transition duration-500 group-hover:scale-110" muted playsinline preload="metadata" :src="item.video_url"></video>
                                </template>
                                <span class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/20 transition group-hover:bg-black/30">
                                    <span class="rounded-full border border-white/25 bg-black/50 px-3 py-1.5 text-[10px] font-extrabold text-white/90 backdrop-blur-sm">معاينة</span>
                                </span>
                            </span>
                        </template>
                        <template x-if="!item.media_type || item.media_type === 'image'">
                            <img :src="item.image_url || item.url" :alt="item.title || ''" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                        </template>
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent px-4 pb-4 pt-12">
                            <p class="text-[10px] font-black uppercase tracking-[.15em] onx-brand-text" x-text="item.category_name || ''"></p>
                            <p class="mt-1 text-[12px] font-extrabold text-white drop-shadow-sm" x-text="item.title || ''"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </section>

    {{-- ══ ④ TESTIMONIALS ═════════════════════════════════════════════════ --}}
    <section class="relative border-y border-white/8 py-16 lg:py-20 grain" data-reveal>
        <div class="absolute left-1/2 top-0 -z-10 h-64 w-96 -translate-x-1/2 rounded-full blur-3xl" :style="'background:' + activeCat().bg_color + '10'"></div>
        <div class="mx-auto max-w-6xl px-6 lg:px-8">
            <div class="reveal-item reveal-delay-1 text-center">
                <p class="mb-2 text-[11px] font-black uppercase tracking-[.22em] onx-brand-text">آراء العملاء</p>
                <h2 class="text-2xl font-black text-white sm:text-3xl">ماذا يقول عملاؤنا</h2>
            </div>
            @if($testimonials->isEmpty())
                <p class="mt-8 text-center text-sm text-white/45">ستُعرض الشهادات المعتمدة هنا عند توفرها.</p>
            @else
                <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($testimonials as $ti => $t)
                        <div class="reveal-card reveal-delay-{{ min($ti + 1, 7) }} glow-hover rounded-[22px] border border-white/8 bg-white/[0.04] p-6 backdrop-blur transition duration-300 hover:-translate-y-1">
                            <div class="mb-4 flex gap-0.5 onx-brand-text" dir="ltr">
                                @for($s = 1; $s <= 5; $s++)
                                    <span class="text-sm">{{ $s <= ($t->rating ?? 5) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-[13px] leading-7 text-white/60 line-clamp-4">{{ $t->content }}</p>
                            <div class="mt-5 flex items-center gap-3 border-t border-white/8 pt-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full onx-brand-bg-soft text-sm font-black onx-brand-text">
                                    {{ mb_substr($t->client_name ?: 'ع', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">{{ $t->client_name ?: 'عميل' }}</p>
                                    @if($t->subtitle)
                                        <p class="text-[10px] text-white/40">{{ $t->subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══ ⑤ STATS ═════════════════════════════════════════════════════════ --}}
    <section class="relative py-14" data-reveal>
        <div class="mx-auto grid max-w-4xl grid-cols-1 gap-px sm:grid-cols-3">
            @foreach([
                ['icon' => '⏱', 'value' => $stats['delivery'], 'label' => 'متوسط التسليم'],
                ['icon' => '😊', 'value' => $hcLabel,            'label' => 'عميل راضٍ'],
                ['icon' => '⭐', 'value' => number_format($stats['avg_rating'], 1), 'label' => 'متوسط التقييم'],
            ] as $si => $stat)
                <div class="reveal-item reveal-delay-{{ $si + 1 }} group px-6 py-8 text-center transition duration-300 hover:-translate-y-0.5
                    {{ $si > 0 ? 'border-t border-white/8 sm:border-t-0 sm:border-r sm:border-white/8' : '' }}">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl onx-brand-bg-soft text-xl transition duration-300">
                        {{ $stat['icon'] }}
                    </div>
                    <div class="text-3xl font-black stat-highlight">{{ $stat['value'] }}</div>
                    <div class="mt-2 text-[11px] font-bold text-white/40">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ══ ⑥ CTA ═══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden py-16 lg:py-20" data-reveal>
        <div class="absolute -left-20 -top-10 h-64 w-64 rounded-full blur-3xl" :style="'background:' + activeCat().bg_color + '12'"></div>
        <div class="absolute -bottom-20 -right-10 h-80 w-80 rounded-full blur-3xl" :style="'background:' + activeCat().bg_color + '0a'"></div>

        <div class="relative mx-auto max-w-3xl px-6 text-center lg:px-8">
            <div class="reveal-item reveal-delay-1 rounded-[28px] border bg-gradient-to-br from-white/[.03] via-white/[.02] to-white/[.03] p-10 backdrop-blur-sm lg:p-14"
                 :style="'border-color:' + activeCat().bg_color + '30'">
                <p class="mb-3 text-[11px] font-black uppercase tracking-[.22em] onx-brand-text">جاهز؟</p>
                <h2 class="reveal-item reveal-delay-2 text-2xl font-black text-white sm:text-3xl">ابدأ مشروعك الآن</h2>
                <p class="reveal-item reveal-delay-3 mx-auto mt-3 max-w-md text-sm leading-relaxed text-white/50">
                    لا تتردد — خطوة واحدة تفصلك عن نتيجة احترافية
                </p>
                <div class="reveal-item reveal-delay-4 mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('booking') }}"
                       class="group inline-flex items-center gap-2 rounded-full px-8 py-3.5 text-sm font-black text-black transition duration-300 hover:-translate-y-0.5 active:scale-[.98]"
                       :style="'background:' + activeCat().bg_color + ';box-shadow:0 0 30px ' + activeCat().bg_color + '35'">
                        احجز الآن
                        <svg class="h-4 w-4 transition duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/{{ $companyWa }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-8 py-3.5 text-sm font-bold text-white/80 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:text-white active:scale-[.98]"
                       @mouseenter="$el.style.borderColor=activeCat().bg_color+'66';$el.style.background=activeCat().bg_color+'14'"
                       @mouseleave="$el.style.borderColor='rgba(255,255,255,.12)';$el.style.background='rgba(255,255,255,.05)'">
                        واتساب
                    </a>
                    @if($companyPhoneRaw !== '')
                        <a href="tel:{{ preg_replace('/\s+/', '', $companyPhoneRaw) }}"
                           class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-8 py-3.5 text-sm font-bold text-white/80 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:text-white active:scale-[.98]"
                           @mouseenter="$el.style.borderColor=activeCat().bg_color+'66';$el.style.background=activeCat().bg_color+'14'"
                           @mouseleave="$el.style.borderColor='rgba(255,255,255,.12)';$el.style.background='rgba(255,255,255,.05)'">
                            اتصال
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ══ VIEWER OVERLAY — portfolio lightbox ════════════════════════════ --}}
    <div
        x-show="viewerOpen"
        x-cloak
        class="fixed inset-0 z-[999] bg-black/95 backdrop-blur-sm"
        role="dialog" aria-modal="true" aria-label="معرض الأعمال"
    >
        <div class="absolute inset-0 flex flex-col">
            <div class="flex items-center justify-between border-b border-white/8 px-4 py-4 md:px-8">
                <div class="text-[11px] font-black uppercase tracking-[0.22em] text-white/40">معرض الاعمال</div>
                <button type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/12 text-white transition duration-300 hover:bg-white hover:text-black"
                    @click="closeViewer()" aria-label="إغلاق">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="relative flex-1 overflow-hidden">
                <button type="button"
                    class="absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/12 bg-black/40 text-orange-500 backdrop-blur transition duration-300 hover:scale-110 hover:bg-white hover:text-black md:left-8"
                    x-show="portfolioList().length > 1" @click="viewerPrev()" aria-label="السابق">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button"
                    class="absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/12 bg-black/40 text-orange-500 backdrop-blur transition duration-300 hover:scale-110 hover:bg-white hover:text-black md:right-8"
                    x-show="portfolioList().length > 1" @click="viewerNext()" aria-label="التالي">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                </button>
                <template x-if="viewerOpen && viewerItem()">
                    <div class="mx-auto grid h-full max-h-[calc(100vh-5rem)] max-w-7xl grid-cols-1 items-center gap-8 overflow-y-auto px-6 py-8 md:px-10 md:py-10 lg:grid-cols-[minmax(0,1.4fr)_380px]"
                         :key="'viewer-body-' + viewerIndex + '-' + (viewerItem().id || 0)">
                        <div class="flex min-h-[300px] items-center justify-center rounded-[24px] border border-white/8 bg-white/[0.03] md:min-h-[520px]">
                            <div class="flex w-full items-center justify-center p-2">
                                <template x-if="viewerItem().media_type === 'youtube' && viewerItem().youtube_video_id">
                                    <div class="aspect-video w-full">
                                        <iframe class="h-[300px] w-full rounded-xl md:h-[520px]"
                                            :src="'https://www.youtube-nocookie.com/embed/' + viewerItem().youtube_video_id + '?autoplay=1&rel=0'"
                                            title="YouTube" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </template>
                                <template x-if="viewerItem().media_type === 'video'">
                                    <video class="max-h-[85vh] w-full rounded-xl object-contain" controls playsinline preload="metadata"
                                        :poster="viewerItem().poster_url || null" :src="viewerItem().video_url"></video>
                                </template>
                                <template x-if="!viewerItem().media_type || viewerItem().media_type === 'image'">
                                    <img :src="viewerItem().image_url || viewerItem().url" :alt="viewerItem().title || ''"
                                         class="mx-auto max-h-[85vh] w-auto object-contain">
                                </template>
                            </div>
                        </div>
                        <div class="space-y-5 lg:pb-0">
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/8 px-3 py-1.5 text-[10px] font-bold text-white/80" x-text="viewerItem().category_name || ''"></span>
                                <span class="inline-flex items-center rounded-full border border-orange-500/25 bg-orange-500/10 px-3 py-1.5 text-[10px] font-bold text-orange-300"
                                    x-show="viewerItem().media_type === 'youtube' || viewerItem().media_type === 'video'"
                                    x-text="viewerItem().media_type === 'youtube' ? 'فيديو' : 'فيديو معاينة'"></span>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black leading-tight text-white md:text-4xl" x-text="viewerItem().title || ''"></h2>
                                <p class="mt-4 text-sm leading-relaxed text-white/60 md:text-base" x-show="viewerItem().caption" x-text="viewerItem().caption"></p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a :href="viewerBookingUrl()"
                                   class="inline-flex items-center gap-2 rounded-full bg-orange-500 px-6 py-3 text-xs font-black text-black transition duration-300 hover:-translate-y-0.5 hover:bg-orange-400 hover:shadow-[0_0_28px_rgba(249,115,22,0.4)]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <span>احجز خدمة مشابهة</span>
                                </a>
                                <a x-show="viewerItem().media_type === 'youtube' && viewerItem().youtube_url"
                                   :href="viewerItem().youtube_url" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-5 py-3 text-xs font-bold text-white/80 transition duration-300 hover:border-orange-500/40 hover:bg-orange-500/8 hover:text-white">
                                    فتح في يوتيوب
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
/* ── Scroll Reveal Observer ── */
(function () {
    const obs = new IntersectionObserver(
        (entries) => entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('in-view'); }),
        { rootMargin: '0px 0px -120px 0px', threshold: 0 }
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
})();

/* ── Alpine component ── */
document.addEventListener('alpine:init', () => {
    Alpine.data('onxServicesPage', () => ({
        idx: 0,
        cats: [],
        viewerOpen: false,
        viewerIndex: 0,
        bookingBase: @json(route('booking')),

        init() {
            const el = document.getElementById('onx-services-categories-payload');
            const raw = (el && el.textContent) ? el.textContent.trim() : '';
            try {
                this.cats = raw ? JSON.parse(raw) : [];
                this.cats.forEach((c) => {
                    const p = c.portfolio || [];
                    c.portfolio = p.map((x) => {
                        if (typeof x === 'string') {
                            return { media_type: 'image', image_url: x, category_name: c.name || '', caption: '', title: '' };
                        }
                        if (x && x.url && !x.media_type) {
                            return { ...x, media_type: 'image', image_url: x.image_url || x.url, caption: x.caption || '', title: x.title || '' };
                        }
                        return { ...x, caption: x.caption || '', title: x.title || '' };
                    });
                });
            } catch (e) {
                this.cats = [];
            }
            const h = (location.hash || '').replace(/^#/, '');
            if (h) {
                const j = this.cats.findIndex((c) => c.slug === h);
                if (j >= 0) this.idx = j;
            }
            this.$nextTick(() => {
                const obs = new IntersectionObserver(
                    (entries) => entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('in-view'); }),
                    { rootMargin: '0px 0px -120px 0px', threshold: 0 }
                );
                document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
            });
        },

        hexToRgb(hex) {
            if (!hex || hex.length < 7) return '232,124,42';
            const r = parseInt(hex.slice(1,3), 16);
            const g = parseInt(hex.slice(3,5), 16);
            const b = parseInt(hex.slice(5,7), 16);
            return r + ',' + g + ',' + b;
        },

        activeCat() {
            return this.cats[this.idx] || {
                services: [], portfolio: [],
                bg_color: '#E87C2A',
                tint_soft: 'rgba(232,124,42,0.22)', tint_edge: 'rgba(232,124,42,0.08)',
                blob1: 'rgba(40,40,40,.65)', blob2: 'rgba(30,30,30,.52)', blob3: 'rgba(55,55,55,.38)',
                name: '', slug: '', description: '', icon: '',
            };
        },

        portfolioList() {
            const c = this.activeCat();
            return (c && c.portfolio) ? c.portfolio : [];
        },

        select(i) {
            this.closeViewer();
            this.idx = i;
            const s = this.activeCat().slug;
            if (s) history.replaceState(null, '', '#' + s);
        },

        blobGrad(c) { return 'radial-gradient(circle,' + c + ' 0%,transparent 65%)'; },

        openViewer(pi) {
            this.viewerIndex = pi;
            this.viewerOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeViewer() {
            this.viewerOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        viewerItem() {
            const list = this.portfolioList();
            return list[this.viewerIndex] || null;
        },

        viewerPrev() {
            const list = this.portfolioList();
            if (list.length < 2) return;
            this.viewerIndex = (this.viewerIndex - 1 + list.length) % list.length;
        },

        viewerNext() {
            const list = this.portfolioList();
            if (list.length < 2) return;
            this.viewerIndex = (this.viewerIndex + 1) % list.length;
        },

        viewerBookingUrl() {
            const it = this.viewerItem();
            if (!it) return this.bookingBase;
            const label = it.category_name || '';
            const title = it.title || '';
            const cap = it.caption || '';
            const mt = it.media_type || 'image';
            let typeLine = 'النوع: صورة';
            if (mt === 'youtube') typeLine = 'النوع: فيديو';
            else if (mt === 'video') typeLine = 'النوع: فيديو معاينة';
            const notes = [`التصنيف: ${label}`, `العمل: ${title}`, cap ? `الوصف: ${cap}` : '', typeLine].filter(Boolean).join(' | ');
            return this.bookingBase + '?notes=' + encodeURIComponent(notes);
        },

        onViewerKeydown(e) {
            if (!this.viewerOpen) return;
            if (e.key === 'Escape') { e.preventDefault(); this.closeViewer(); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); this.viewerPrev(); }
            if (e.key === 'ArrowRight') { e.preventDefault(); this.viewerNext(); }
        },
    }));
});
</script>
@endpush

@endsection