@extends('layouts.front_tailwind')

@section('title', 'ONX Edge — إنتاج بصري فاخر')
@section('meta_description', 'ONX Edge — شركة إنتاج بصري متخصصة في الإعلانات، الحفلات، والتغطيات الراقية. أفلام وإعلانات وتجارب بصرية تترك انطباعًا لا يُنسى.')
@section('has_hero', true)

@push('styles')
<style>
/* ══ ONX EDGE — HOME v2 ══════════════════════════════════════════════ */

/* Ticker animation */
@keyframes ticker {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
.ticker-inner { animation: ticker 22s linear infinite; }
.ticker-inner:hover { animation-play-state: paused; }

/* Glitch effect on heading */
@keyframes glitch1 {
    0%, 90%, 100% { clip-path: none; transform: translate(0); }
    92%            { clip-path: polygon(0 30%, 100% 30%, 100% 50%, 0 50%); transform: translate(-3px, 0); }
    94%            { clip-path: polygon(0 65%, 100% 65%, 100% 80%, 0 80%); transform: translate(3px, 0); }
    96%            { clip-path: polygon(0 10%, 100% 10%, 100% 25%, 0 25%); transform: translate(-2px, 0); }
}
.glitch { animation: glitch1 8s ease-in-out infinite; }

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

/* Shimmer border */
@keyframes borderSpin {
    to { --border-angle: 360deg; }
}
@property --border-angle {
    syntax: '<angle>'; initial-value: 0deg; inherits: false;
}
.border-spin {
    --border-angle: 0deg;
    animation: borderSpin 6s linear infinite;
    background:
        linear-gradient(#0a0a0a, #0a0a0a) padding-box,
        conic-gradient(from var(--border-angle), #c8490a 0%, transparent 30%, transparent 70%, #c8490a 100%) border-box;
    border: 1px solid transparent;
}

/* Number odometer */
.count-num { font-variant-numeric: tabular-nums; }

/* Card hover glow */
.glow-hover { transition: box-shadow .3s ease; }
.glow-hover:hover { box-shadow: 0 0 40px rgba(200,73,10,.18), 0 24px 60px rgba(0,0,0,.5); }

/* Video reel strip */
.reel-strip {
    background: repeating-linear-gradient(
        90deg,
        transparent 0px, transparent 18px,
        rgba(255,255,255,.06) 18px, rgba(255,255,255,.06) 20px
    );
}

/* Grain */
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

/* Diagonal split hero */
.hero-diagonal::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(108deg, #050505 52%, transparent 52.5%);
    z-index: 1;
    pointer-events: none;
}

/* Number highlight */
.stat-highlight {
    background: linear-gradient(135deg, #ff6b1a 0%, #c8490a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════════ --}}
<section class="relative isolate min-h-[80vh] overflow-hidden flex items-center grain" data-reveal>

    {{-- Background image --}}
    <div class="absolute inset-0 -z-20">
        <img src="{{ asset('img/hero-bg1.jpg') }}"
             alt="" aria-hidden="true"
             class="h-full w-full object-cover opacity-[0.16]">
    </div>

    {{-- Gradient overlays --}}
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#080808] via-[#050505]/90 to-[#020202]"></div>
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_80%_60%_at_60%_-10%,rgba(200,73,10,.18),transparent)]"></div>

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

    <div class="mx-auto w-full max-w-7xl px-6 pt-28 pb-12 lg:px-8 lg:pt-31 lg:pb-16">
        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">

            {{-- LEFT — Copy --}}
            <div class="order-2 lg:order-1">

                {{-- Tag --}}
                <div class="reveal-item reveal-delay-1 mb-6 inline-flex items-center gap-2.5 rounded-full border border-orange-500/25 bg-orange-500/8 px-4 py-2 text-[11px] font-black uppercase tracking-[.22em] text-orange-300">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-500 opacity-60"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-orange-500"></span>
                    </span>
                    Creative Production Studio — الجزائر
                </div>

                {{-- H1 --}}
                <h1 class="reveal-item reveal-delay-2 glitch text-[clamp(1.5rem,2.8vw,2.2rem)] font-black leading-[1.20] tracking-tight text-white">
    نصنع
    <span class="text-orange-500"> أفلامًا</span>
    <br>وإعلانات
</h1>
<p class="reveal-item reveal-delay-2 mt-2 text-[clamp(1rem,2vw,1.5rem)] font-extrabold text-white/45 tracking-tight">
    تترك أثرًا لا يُنسى.
</p>

                <p class="reveal-item reveal-delay-3 mt-6 max-w-xl text-sm leading-8 text-white/60">
                    شركة إنتاج بصري متخصصة في الإعلانات، الحفلات، والتغطيات الراقية.
                    نحوّل الفكرة إلى صورة ذات حضور قوي — بتفاصيل دقيقة وإيقاع سينمائي.
                </p>

                {{-- CTAs --}}
                <div class="reveal-item reveal-delay-4 mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('booking') }}"
                       class="group relative inline-flex items-center gap-2 overflow-hidden rounded-full bg-orange-500 px-7 py-3.5 text-[13px] font-black text-black transition duration-300 hover:-translate-y-0.5 hover:bg-orange-400 hover:shadow-[0_0_40px_rgba(249,115,22,.35)] active:scale-[.98]">
                        <span class="relative z-10">احجز مشروعك الآن</span>
                        <svg class="relative z-10 h-4 w-4 transition duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <a href="/portfolio"
                       class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-7 py-3.5 text-[13px] font-extrabold text-white/80 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:border-orange-500/40 hover:bg-orange-500/8 hover:text-white active:scale-[.98]">
                        شاهد أعمالنا
                    </a>
                </div>

                {{-- Feature chips --}}
                <div class="reveal-item reveal-delay-5 mt-10 grid grid-cols-3 gap-3">
                    @foreach([
                        ['🎬','Cinema Look','لقطات سينمائية بهوية بصرية واضحة'],
                        ['⚡','Precise Flow','تنفيذ منظم من الفكرة للتسليم'],
                        ['🔥','Brand Impact','صورة ترفع قيمة براندك'],
                    ] as $chip)
                    <div class="glow-hover rounded-2xl border border-white/8 bg-white/[.04] p-3.5 backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:border-orange-500/25">
                        <div class="mb-2 text-xl">{{ $chip[0] }}</div>
                        <div class="text-[12px] font-black text-white">{{ $chip[1] }}</div>
                        <div class="mt-1 text-[10px] leading-5 text-white/50">{{ $chip[2] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT — Visual card --}}
            <div class="reveal-card reveal-delay-3 order-1 lg:order-2">
                <div class="relative mx-auto max-w-md">
                    {{-- Glow halo --}}
                    <div class="absolute -inset-10 rounded-[50%] bg-orange-500/12 blur-3xl"></div>

                    {{-- Main image card --}}
                    <div class="border-spin relative overflow-hidden rounded-[28px] shadow-[0_32px_90px_rgba(0,0,0,.6)]">
                        <img src="{{ asset('img/events.jpg') }}"
                             alt="ONX Production"
                             class="h-[440px] w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

                        {{-- Top badges --}}
                        <div class="absolute left-0 right-0 top-0 flex items-center justify-between p-4">
                            <span class="rounded-full border border-white/10 bg-black/40 px-3 py-1 text-[10px] font-black tracking-[.2em] text-white/60 backdrop-blur">
                                ONX FRAME
                            </span>
                            <span class="rounded-full border border-orange-500/40 bg-orange-500/15 px-3 py-1 text-[10px] font-black tracking-wide text-orange-300">
                                ● LIVE
                            </span>
                        </div>

                        {{-- Bottom caption --}}
                        <div class="absolute bottom-0 left-0 right-0 p-5">
                            <p class="text-[10px] font-black uppercase tracking-[.2em] text-orange-400">Featured Visual</p>
                            <h2 class="mt-1.5 text-xl font-black text-white">صورة تُحَس قبل أن تُشاهَد</h2>
                            <p class="mt-1.5 text-xs leading-6 text-white/60">من أول frame لآخر لقطة — كل شيء مدروس.</p>
                        </div>
                    </div>

                    {{-- Floating stat badges --}}
                    <div class="absolute -bottom-5 -right-4 flex items-center gap-2.5 rounded-2xl border border-white/10 bg-[#0d0d0d]/90 px-4 py-3 shadow-xl backdrop-blur">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500/20 text-lg">🏆</div>
                        <div>
                            <div class="text-lg font-black text-white count-num" data-target="200">200+</div>
                            <div class="text-[10px] text-white/50">مشروع منجز</div>
                        </div>
                    </div>
                    <div class="absolute -top-5 -left-4 flex items-center gap-2.5 rounded-2xl border border-white/10 bg-[#0d0d0d]/90 px-4 py-3 shadow-xl backdrop-blur">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500/20 text-lg">⭐</div>
                        <div>
                            <div class="text-lg font-black text-white">5.0</div>
                            <div class="text-[10px] text-white/50">تقييم العملاء</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══ TICKER ══════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden border-y border-white/8 bg-[#080808] py-3.5">
    <div class="reel-strip absolute inset-0 opacity-50"></div>
    <div class="ticker-inner flex items-center gap-0 whitespace-nowrap relative z-10">
        @php
        $items = ['إعلانات تجارية', '●', 'تغطية فعاليات', '●', 'إنتاج سينمائي', '●', 'هوية بصرية', '●', 'تصوير احترافي', '●', 'مونتاج متقن', '●', 'محتوى تسويقي', '●', 'أفلام قصيرة', '●'];
        $repeated = array_merge($items, $items);
        @endphp
        @foreach($repeated as $item)
            <span class="px-5 text-[11px] font-black uppercase tracking-[.2em] {{ $item === '●' ? 'text-orange-500' : 'text-white/35' }}">{{ $item }}</span>
        @endforeach
    </div>
</div>

{{-- ══ STATS ════════════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-14 lg:px-8" data-reveal>
    <div class="reveal-card reveal-delay-1 grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach([
            ['200', '+', 'مشروع منجز', '🎬'],
            ['5',   '+', 'سنوات خبرة',  '📅'],
            ['150', '+', 'عميل راضٍ',   '🤝'],
            ['3',   '',  'تخصصات إنتاج','⚡'],
        ] as $i => [$num, $suf, $lbl, $icon])
        <div class="glow-hover rounded-[22px] border border-white/8 bg-white/[.03] p-5 text-center backdrop-blur transition duration-300 hover:border-orange-500/25">
            <div class="mb-1 text-2xl">{{ $icon }}</div>
            <div class="text-[clamp(2rem,4vw,2.8rem)] font-black leading-none stat-highlight count-num" data-target="{{ $num }}">{{ $num }}{{ $suf }}</div>
            <div class="mt-2 text-xs text-white/50">{{ $lbl }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ══ ABOUT ════════════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">

        {{-- Main about card --}}
        <div class="reveal-card reveal-delay-1 relative overflow-hidden rounded-[28px] border border-white/8 bg-white/[.03] p-7 shadow-[0_20px_60px_rgba(0,0,0,.35)] backdrop-blur-xl grain">
            <div class="absolute -left-20 -top-10 h-48 w-48 rounded-full bg-orange-500/10 blur-3xl"></div>
            <div class="relative z-10">
                <p class="mb-3 text-[11px] font-black uppercase tracking-[.3em] text-orange-400">من نحن</p>
                <h2 class="max-w-xl text-2xl font-black leading-tight sm:text-3xl">
                    لسنا مجرد تصوير —<br>
                    <span class="text-white/50">بل بناء حضور بصري كامل.</span>
                </h2>
                <p class="mt-5 text-sm leading-8 text-white/60">
                    ONX Edge هوية إنتاجية تؤمن بأن كل مشروع يستحق أن يبدو استثنائيًا.
                    سواء كان إعلانًا تجاريًا، تغطية حفل، أو محتوى فاخرًا —
                    نشتغل على الإضاءة، الزاوية، والإيقاع كأن الصورة تملك نبضها الخاص.
                </p>
                <a href="/portfolio"
                   class="mt-6 inline-flex items-center gap-2 text-[13px] font-black text-orange-400 transition hover:text-orange-300">
                    شاهد أعمالنا
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
            </div>
        </div>

        {{-- Side cards --}}
        <div class="grid gap-4">
            @foreach([
                ['🎬','رؤية سينمائية','لقطات مدروسة وإضاءة محسوبة يجعلان العمل أعمق وأكثر أناقة.','reveal-delay-2'],
                ['⚙️','تنفيذ منظم','من الفكرة لآخر تفصيل — بلا فوضى، بلا تأخير.','reveal-delay-3'],
            ] as [$icon, $title, $desc, $delay])
            <div class="reveal-card {{ $delay }} glow-hover rounded-[22px] border border-white/8 bg-white/[.03] p-5 backdrop-blur transition duration-300 hover:border-white/16">
                <div class="mb-3 text-2xl">{{ $icon }}</div>
                <h3 class="text-base font-black">{{ $title }}</h3>
                <p class="mt-2 text-xs leading-6 text-white/55">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ SERVICES ═════════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="reveal-item reveal-delay-1 mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[11px] font-black uppercase tracking-[.28em] text-orange-400">الخدمات</p>
            <h2 class="text-2xl font-black sm:text-3xl">حلول بصرية تُلفت وتُقنع</h2>
        </div>
        <a href="/services"
           class="w-fit rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-extrabold text-white/70 transition hover:border-orange-500/40 hover:text-white">
            كل الخدمات ←
        </a>
    </div>

    <div class="grid gap-5 md:grid-cols-3">
        @foreach([
            ['events',     'img/events.jpg',    'EVENTS',      'فعاليات ومناسبات',   'تصوير وتغطية بأسلوب سينمائي راقٍ.',        'text-orange-400', 'hover:border-orange-500/30'],
            ['business',   'img/marketing.jpg', 'BUSINESS',    'محتوى تجاري',        'إعلانات وتسويق وبناء علامة تجارية.',        'text-sky-400',    'hover:border-sky-400/30'],
            ['production', 'img/hero-bg1.jpg',  'PRODUCTION',  'إنتاج إبداعي',       'مشاريع سينمائية خاصة — تواصل مباشر.',      'text-violet-300', 'hover:border-violet-500/30'],
        ] as [$cat, $img, $badge, $title, $desc, $clr, $hoverBorder])
        <a href="{{ route('services.index', ['cat' => $cat]) }}#services-grid"
           class="reveal-card reveal-delay-{{ $loop->index + 2 }} group relative overflow-hidden rounded-[28px] border border-white/8 bg-white/[.04] shadow-[0_20px_60px_rgba(0,0,0,.4)] transition duration-500 hover:-translate-y-2 {{ $hoverBorder }} glow-hover">
            <div class="absolute inset-0">
                <img src="{{ asset($img) }}" alt="{{ $title }}"
                     class="h-full w-full min-h-[300px] object-cover opacity-40 transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/55 to-black/10"></div>
            </div>
            <div class="relative flex min-h-[300px] flex-col justify-end p-6">
                <div class="mb-4 w-fit rounded-full border border-white/10 bg-black/30 px-3 py-1.5 text-[9px] font-black tracking-[.22em] text-white/60 backdrop-blur">
                    {{ $badge }}
                </div>
                <h3 class="text-lg font-black sm:text-xl">{{ $title }}</h3>
                <p class="mt-1.5 text-xs leading-6 text-white/60">{{ $desc }}</p>
                <div class="mt-4 text-xs font-black {{ $clr }} transition duration-300 group-hover:translate-x-[-4px]">
                    استكشف الخدمات ←
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- ══ SHOWCASE / PORTFOLIO ════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="reveal-item reveal-delay-1 mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[11px] font-black uppercase tracking-[.28em] text-orange-400">لقطات من الروح</p>
            <h2 class="text-2xl font-black sm:text-3xl">أعمال لا تكتفي بأن تبدو جميلة</h2>
        </div>
        <a href="/portfolio"
           class="w-fit rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-extrabold text-white/70 transition hover:border-orange-500/40 hover:text-white">
            كل الأعمال ←
        </a>
    </div>

    @if(isset($homeFeatured) && $homeFeatured->count())
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($homeFeatured->take(3) as $item)
        @php
            $cover = null;
            if ($item->media_type === 'youtube' && !empty($item->youtube_video_id))
                $cover = 'https://img.youtube.com/vi/'.$item->youtube_video_id.'/hqdefault.jpg';
            elseif (!empty($item->image_path))
                $cover = asset($item->image_path);
            $d = ['reveal-delay-2','reveal-delay-3','reveal-delay-4'][$loop->index % 3];
        @endphp
        <div class="reveal-card {{ $d }} group relative overflow-hidden rounded-[24px] border border-white/8 glow-hover transition duration-300 hover:-translate-y-1 hover:border-white/18">
            <div class="relative h-[320px]">
                @if($cover)
                    <img src="{{ $cover }}" alt="{{ $item->title }}"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-108">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-white/5 text-xs text-white/30">لا توجد صورة</div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/25 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-5">
                    <p class="text-[9px] font-black uppercase tracking-[.22em] text-orange-400">
                        {{ $item->service_type === 'ads' ? 'BRAND WORK' : 'EVENT STORY' }}
                    </p>
                    <h3 class="mt-1.5 text-lg font-black text-white">{{ $item->title }}</h3>
                    @if(!empty($item->caption))
                        <p class="mt-1 text-xs leading-5 text-white/60">{{ $item->caption }}</p>
                    @endif
                    @if($item->media_type === 'youtube' && !empty($item->youtube_url))
                        <a href="{{ $item->youtube_url }}" target="_blank"
                           class="mt-3 inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[11px] font-extrabold text-white hover:border-orange-500/40 hover:bg-orange-500/10">
                            مشاهدة الفيديو ▶
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="rounded-[24px] border border-white/8 bg-white/[.03] p-10 text-center">
        <div class="mb-3 text-4xl">🎬</div>
        <p class="text-sm text-white/50">سيتم عرض الأعمال المختارة هنا بعد إضافتها من لوحة التحكم.</p>
    </div>
    @endif
</section>

{{-- ══ WHY ONX ══════════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="reveal-card reveal-delay-1 relative overflow-hidden rounded-[32px] border border-white/8 bg-white/[.02] p-7 backdrop-blur-xl grain sm:p-9">
        <div class="absolute -right-20 top-0 h-64 w-64 rounded-full bg-orange-500/8 blur-3xl"></div>

        <div class="relative z-10 mb-9 text-center">
            <p class="mb-2 text-[11px] font-black uppercase tracking-[.28em] text-orange-400">لماذا ONX Edge</p>
            <h2 class="text-2xl font-black sm:text-3xl">لأن النتيجة يجب أن تبدو بمستوى يليق بك</h2>
        </div>

        <div class="relative z-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['🎥','زاوية نظر مدروسة','الصورة ليست مصادفة، بل قرار بصري محسوب من أول لقطة.'],
                ['🧠','فهم الهدف','نبدأ من فكرة المشروع، لا من الكاميرا فقط.'],
                ['⚙️','تنفيذ منظم','كل مرحلة في مكانها — لا فوضى ولا مفاجآت.'],
                ['🔥','حضور بصري قوي','نبحث عن التأثير الحقيقي، لا عن مجرد «فيديو محترم».'],
            ] as $i => [$icon, $title, $desc])
            <div class="reveal-card reveal-delay-{{ $i + 2 }} glow-hover rounded-[20px] border border-white/8 bg-black/20 p-5 transition duration-300 hover:-translate-y-0.5 hover:border-orange-500/20">
                <div class="mb-3 text-2xl">{{ $icon }}</div>
                <h3 class="text-sm font-black">{{ $title }}</h3>
                <p class="mt-2 text-xs leading-6 text-white/55">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ TESTIMONIALS ════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="reveal-item reveal-delay-1 mb-10 text-center">
        <p class="mb-2 text-[11px] font-black uppercase tracking-[.28em] text-orange-400">آراء العملاء</p>
        <h2 class="text-2xl font-black sm:text-3xl">ما قاله من تعاملوا معنا</h2>
        <p class="mx-auto mt-3 max-w-xl text-xs leading-7 text-white/55">
            ثقة عملائنا جزء أساسي من هوية ONX Edge.
        </p>
    </div>

    @if(isset($testimonials) && $testimonials->isNotEmpty())
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($testimonials as $t)
        @php $d = ['reveal-delay-2','reveal-delay-3','reveal-delay-4','reveal-delay-3','reveal-delay-4','reveal-delay-5'][$loop->index % 6]; @endphp
        <div class="reveal-card {{ $d }} glow-hover rounded-[24px] border border-white/8 bg-white/[.03] p-5 backdrop-blur-xl transition duration-300 hover:-translate-y-0.5 hover:border-white/16 {{ $loop->last && $testimonials->count() % 3 === 1 ? 'md:col-span-2 xl:col-span-1' : '' }}">
            <div class="mb-4 flex gap-0.5 text-orange-500 text-sm">
                @for($i = 0; $i < (int)$t->rating; $i++)★@endfor
            </div>
            <p class="text-sm leading-7 text-white/75">{{ $t->content }}</p>
            <div class="mt-5 flex items-center gap-3 border-t border-white/6 pt-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-500/15 text-sm font-black text-orange-400">
                    {{ $t->initial ?: mb_substr($t->client_name, 0, 1) }}
                </div>
                <div>
                    <span class="block text-[13px] font-bold text-white">{{ $t->client_role ?: $t->client_name }}</span>
                    @if($t->subtitle)<span class="text-[11px] text-white/45">{{ $t->subtitle }}</span>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="rounded-[24px] border border-white/8 bg-white/[.03] p-10 text-center text-sm text-white/50">
        سيتم عرض آراء العملاء هنا بعد إضافتها من لوحة التحكم.
    </div>
    @endif
</section>

{{-- ══ CTA ══════════════════════════════════════════════════════════════ --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8" data-reveal>
    <div class="reveal-card reveal-delay-1 relative overflow-hidden rounded-[32px] border border-orange-500/18 bg-gradient-to-br from-orange-500/10 via-white/[.03] to-white/[.03] p-8 shadow-[0_32px_90px_rgba(0,0,0,.5)] sm:p-12 grain">
        <div class="absolute -left-24 top-1/2 h-64 w-64 -translate-y-1/2 rounded-full bg-orange-500/12 blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 h-48 w-48 rounded-full bg-white/[.03] blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-2xl text-center">
            <p class="mb-3 text-[11px] font-black uppercase tracking-[.28em] text-orange-400">ابدأ الآن</p>
            <h2 class="text-2xl font-black leading-tight sm:text-4xl">
                هل لديك مشروع يحتاج<br>
                <span class="text-orange-500">حضورًا بصريًا حقيقيًا؟</span>
            </h2>
            <p class="mt-5 text-sm leading-7 text-white/60">
                دعنا نحوّل فكرتك إلى عمل بصري فاخر — يليق بالبراند، بالمناسبة، وبالصورة التي تريد أن يراك الناس بها.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('booking') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-orange-500 px-8 py-3.5 text-sm font-black text-black transition duration-300 hover:-translate-y-0.5 hover:bg-orange-400 hover:shadow-[0_0_40px_rgba(249,115,22,.35)] active:scale-[.98]">
                    احجز الآن
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <a href="https://wa.me/213540573518" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-8 py-3.5 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:border-orange-500/40 hover:bg-orange-500/8 active:scale-[.98]">
                    <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    واتساب
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Scroll Reveal ────────────────────────────────────────
const revealSections = document.querySelectorAll('[data-reveal]');
const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('in-view');
            io.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });
revealSections.forEach(el => io.observe(el));

// ── Count-up ──────────────────────────────────────────────
const countEls = document.querySelectorAll('[data-target]');
const countIO = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseInt(el.dataset.target);
        let cur = 0;
        const step = Math.max(1, Math.ceil(target / 45));
        const suffix = el.textContent.replace(/\d+/, '').trim();
        const t = setInterval(() => {
            cur = Math.min(cur + step, target);
            el.textContent = cur + suffix;
            if (cur >= target) clearInterval(t);
        }, 28);
        countIO.unobserve(el);
    });
}, { threshold: 0.5 });
countEls.forEach(el => countIO.observe(el));
</script>
@endpush