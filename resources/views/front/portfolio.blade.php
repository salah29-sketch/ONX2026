@extends('layouts.front_tailwind')

@section('title', 'ONX | الأعمال')
@section('meta_description', 'أعمال ONX — نماذج من مشاريعنا في الإعلانات، الفعاليات، والتجارب البصرية.')
@section('has_hero', true)
@section('content')

{{-- HERO --}}
<section class="relative isolate overflow-hidden border-b border-white/10">
    <div class="absolute inset-0 -z-10">
        @php
            // FIX #1: fallback يدعم YouTube thumbnail إن لم توجد صورة
            $heroImage = null;
            if ($heroItem) {
                if (!empty($heroItem->image_path)) {
                    $heroImage = asset($heroItem->image_path);
                } elseif (!empty($heroItem->youtube_video_id)) {
                    $heroImage = 'https://img.youtube.com/vi/' . $heroItem->youtube_video_id . '/hqdefault.jpg';
                }
            }
            $heroImage ??= asset('img/events.jpg');
        @endphp
        <img src="{{ $heroImage }}" alt="ONX Portfolio" class="h-full w-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/80 to-[#050505]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(255,106,0,0.14),transparent_28%),radial-gradient(circle_at_20%_80%,rgba(255,106,0,0.06),transparent_26%)]"></div>
    </div>

    <div class="mx-auto grid min-h-[60vh] max-w-7xl items-center gap-8 px-6 py-14 lg:grid-cols-[1.02fr_.98fr] lg:gap-10 lg:px-8 lg:py-16">
        <div class="order-1">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-[10px] font-bold text-white/60 opacity-0 backdrop-blur animate-fade-in-up">
                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                Portfolio
            </div>
            <h1 class="max-w-xl text-lg font-black leading-[1.25] text-white opacity-0 sm:text-xl lg:text-2xl animate-fade-in-up animate-delay-100">
                أعمال <span class="text-orange-500">تُرى</span> وتُتذكر
            </h1>
            <p class="mt-3 max-w-lg text-[11px] leading-6 text-white/60 opacity-0 sm:text-xs animate-fade-in-up animate-delay-200">
                نماذج من مشاريعنا في الإعلانات، الفعاليات، والتجارب البصرية.
            </p>
            <div class="mt-5 flex flex-wrap gap-3 opacity-0 animate-fade-in-up animate-delay-300">
                <a href="#portfolio-grid" class="inline-flex items-center justify-center rounded-full bg-orange-500 px-5 py-2 text-[11px] font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)] active:scale-[0.98]">استكشف الأعمال</a>
                <a href="/booking" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-5 py-2 text-[11px] font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10 active:scale-[0.98]">ابدأ مشروعك</a>
            </div>
        </div>

        <div class="order-2 opacity-0 animate-fade-in-up animate-delay-300">
            <div class="relative mx-auto max-w-lg">
                <div class="absolute -inset-8 rounded-[38px] bg-orange-500/10 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-[24px] border border-white/10 bg-white/5 shadow-[0_24px_80px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                    <img src="{{ $heroImage }}" alt="ONX Portfolio" class="h-[320px] w-full object-cover opacity-95 lg:h-[400px]">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                    <div class="absolute left-0 right-0 top-0 flex items-start justify-between p-3.5">
                        <div class="inline-flex rounded-full border border-white/10 bg-black/35 px-2.5 py-1 text-[10px] font-extrabold tracking-[0.18em] text-white/70 backdrop-blur">ONX FRAME</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- REELS --}}
@if(isset($reelItems) && $reelItems->count())
<section class="mx-auto max-w-7xl py-14 lg:px-8">
    <div class="mb-6 flex items-baseline justify-between px-6 lg:px-0">
        <div>
            <p class="mb-1 text-[10px] font-extrabold uppercase tracking-[0.25em] text-orange-400">فيديوهات قصيرة</p>
            <h2 class="text-lg font-black sm:text-xl">الريلز والفيديوهات</h2>
        </div>
        <span class="text-xs text-white/30">{{ $reelItems->count() }} عمل</span>
    </div>

    <div id="reelsCarousel" class="reels-carousel flex gap-4 overflow-x-auto px-6 pb-4 lg:px-0" style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none;">
        @foreach($reelItems as $reel)
            @php
                $thumb = null;
                if ($reel->reel_source === 'youtube' && $reel->youtube_video_id) {
                    $thumb = 'https://img.youtube.com/vi/' . $reel->youtube_video_id . '/hqdefault.jpg';
                } elseif ($reel->image_path) {
                    $thumb = asset($reel->image_path);
                }
                $srcLabel = $reel->reel_source === 'youtube' ? 'YouTube' : 'Video';
                $catName  = $reel->categoryRelation?->name ?? '—';
            @endphp

            <button
                type="button"
                class="reel-open-btn reel-card group relative block flex-shrink-0 overflow-hidden rounded-[24px] border border-white/10 bg-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.35)] transition duration-500 hover:-translate-y-1 hover:border-white/20 text-right"
                style="scroll-snap-align: start; width: calc(50% - 8px);"
                data-reel-source="{{ $reel->reel_source }}"
                data-video-path="{{ $reel->reel_source === 'mp4' && $reel->video_path ? asset($reel->video_path) : '' }}"
                data-youtube-id="{{ $reel->youtube_video_id ?? \App\Models\Content\PortfolioItem::extractYoutubeVideoId($reel->reel_url) ?? '' }}"
                data-title="{{ e($reel->title) }}"
                data-caption="{{ e($reel->caption ?? '') }}"
                data-cat="{{ $catName }}"
            >
                <div class="relative w-full" style="padding-top:177.78%">
                    <div class="absolute inset-0">
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $reel->title }}"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                        @else
                            <div class="h-full w-full bg-white/5 flex items-center justify-center text-xs font-bold text-white/40">لا توجد صورة</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                        <div class="absolute inset-x-0 top-0 flex items-center justify-between p-3">
                            <div class="inline-flex rounded-full border border-white/10 bg-white/10 px-2.5 py-1 text-[9px] font-extrabold tracking-[0.18em] text-white/70 backdrop-blur">{{ $catName }}</div>
                            <div class="inline-flex rounded-full border border-white/10 bg-white/10 px-2.5 py-1 text-[9px] font-extrabold tracking-wide text-white/75">{{ $srcLabel }}</div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-white/10 backdrop-blur transition duration-300 group-hover:bg-white/25 group-hover:scale-110">
                                <svg width="14" height="14" viewBox="0 0 12 12" fill="none">
                                    <path d="M2.5 1.5L10 6L2.5 10.5V1.5Z" fill="rgba(255,255,255,0.9)"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-3">
                            <h3 class="text-sm font-black text-white sm:text-base">{{ $reel->title }}</h3>
                            @if(!empty($reel->caption))
                                <p class="mt-1 text-[10px] leading-4 text-white/70 line-clamp-2">{{ $reel->caption }}</p>
                            @endif
                            <div class="mt-2 inline-flex rounded-full border border-white/15 bg-white/5 px-2.5 py-1 text-[10px] font-extrabold text-white transition group-hover:border-orange-500/40 group-hover:bg-orange-500/10">فتح المعاينة</div>
                        </div>
                    </div>
                </div>
            </button>
        @endforeach
    </div>
</section>
@endif

{{-- FEATURED --}}
@if(isset($featuredItems) && $featuredItems->count())
<section class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
    <div class="mb-6 opacity-0 animate-fade-in-up animate-delay-200">
        <p class="mb-1 text-[10px] font-extrabold uppercase tracking-[0.25em] text-orange-400">أعمال مختارة</p>
        <h2 class="text-lg font-black sm:text-xl">مشاريع نحب أن تبدأ بها</h2>
    </div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($featuredItems->take(3) as $item)
            @php
                $coverImage = null;
                if ($item->media_type === 'youtube' && !empty($item->youtube_video_id)) {
                    $coverImage = 'https://img.youtube.com/vi/' . $item->youtube_video_id . '/hqdefault.jpg';
                } elseif (!empty($item->image_path)) {
                    $coverImage = asset($item->image_path);
                }
                $catName = $item->categoryRelation?->name ?? '—';
                $stagger = ['animate-delay-300','animate-delay-400','animate-delay-500'][$loop->index % 3];
            @endphp
            <article class="portfolio-card group relative overflow-hidden rounded-[24px] border border-white/10 bg-white/5 opacity-0 shadow-[0_20px_50px_rgba(0,0,0,0.35)] transition duration-300 hover:-translate-y-1 hover:border-white/20 animate-fade-in-up {{ $stagger }}"
                data-category="{{ $item->category_id }}" data-title="{{ e($item->title) }}"
                data-caption="{{ e($item->caption) }}" data-media-type="{{ $item->media_type }}"
                data-image="{{ $item->image_path ? asset($item->image_path) : '' }}"
                data-youtube-id="{{ $item->youtube_video_id }}" data-service-label="{{ $catName }}"
                data-video-path="">
                <button type="button" class="portfolio-open-btn block w-full text-right">
                    <div class="relative h-[320px] w-full overflow-hidden">
                        @if($coverImage)
                            <img src="{{ $coverImage }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-700 grayscale group-hover:grayscale-0 group-hover:scale-110">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-white/5 text-xs font-bold text-white/40">لا توجد صورة</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                        <div class="absolute inset-x-0 top-0 flex items-center justify-between p-4">
                            <div class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[10px] font-extrabold tracking-[0.18em] text-white/70 backdrop-blur">{{ $catName }}</div>
                            @if($item->media_type === 'youtube' && !empty($item->youtube_video_id))
                                <div class="inline-flex rounded-full border border-orange-500/30 bg-orange-500/10 px-3 py-1.5 text-[10px] font-extrabold tracking-wide text-orange-300">فيديو</div>
                            @endif
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <h3 class="text-lg font-black text-white sm:text-xl">{{ $item->title }}</h3>
                            @if(!empty($item->caption))
                                <p class="mt-1 text-xs leading-5 text-white/70 line-clamp-2">{{ $item->caption }}</p>
                            @endif
                            <div class="mt-3 inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[11px] font-extrabold text-white transition group-hover:border-orange-500/40 group-hover:bg-orange-500/10">فتح المعاينة</div>
                        </div>
                    </div>
                </button>
            </article>
        @endforeach
    </div>
</section>
@endif

{{-- FILTERS + GRID --}}
<section id="portfolio-grid" class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 opacity-0 sm:flex-row sm:items-end sm:justify-between animate-fade-in-up animate-delay-200">
        <div>
            <p class="mb-1 text-[10px] font-extrabold uppercase tracking-[0.25em] text-orange-400">كل الأعمال</p>
            <h2 class="text-lg font-black sm:text-xl">نماذج من شغلنا</h2>
        </div>
        <button id="randomShotBtn" type="button"
                class="inline-flex w-fit rounded-full border border-white/10 bg-white/5 px-4 py-2.5 text-xs font-extrabold text-white/80 transition duration-200 hover:border-orange-500/40 hover:bg-orange-500/10 hover:text-white active:scale-[0.98]">
            اختر عملاً عشوائيًا
        </button>
    </div>

    <div class="mb-8 flex flex-wrap gap-3">
        <button class="filter-btn inline-flex rounded-full bg-orange-500 px-5 py-2.5 text-xs font-black text-black transition duration-200 active:scale-[0.98]" type="button" data-filter="all">الكل</button>
        @foreach($categories as $cat)
            <button class="filter-btn inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-5 py-2.5 text-xs font-extrabold text-white transition duration-200 hover:border-orange-500/40 hover:bg-orange-500/10 active:scale-[0.98]"
                    type="button" data-filter="{{ $cat->id }}">
                @if($cat->icon)<span style="font-size:13px">{{ $cat->icon }}</span>@endif
                {{ $cat->name }}
            </button>
        @endforeach
    </div>

    @if(isset($items) && $items->count())
        <div id="portfolioGrid" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($items as $item)
                @php
                    $coverImage = null;
                    if ($item->media_type === 'youtube' && !empty($item->youtube_video_id)) {
                        $coverImage = 'https://img.youtube.com/vi/' . $item->youtube_video_id . '/hqdefault.jpg';
                    } elseif (!empty($item->image_path)) {
                        $coverImage = asset($item->image_path);
                    }
                    $catName = $item->categoryRelation?->name ?? '—';
                @endphp
                <article class="portfolio-card group relative overflow-hidden rounded-[24px] border border-white/10 bg-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.35)] transition duration-300 hover:-translate-y-1 hover:border-white/20"
                    data-category="{{ $item->category_id }}" data-title="{{ e($item->title) }}"
                    data-caption="{{ e($item->caption) }}" data-media-type="{{ $item->media_type }}"
                    data-image="{{ $item->image_path ? asset($item->image_path) : '' }}"
                    data-youtube-id="{{ $item->youtube_video_id }}" data-service-label="{{ $catName }}"
                    data-video-path="">
                    <button type="button" class="portfolio-open-btn block w-full text-right">
                        <div class="relative h-[320px] w-full overflow-hidden">
                            @if($coverImage)
                                <img src="{{ $coverImage }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-700 group-hover:grayscale-0 group-hover:scale-110">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-white/5 text-xs font-bold text-white/40">لا توجد صورة</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-4">
                                <div class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[10px] font-extrabold tracking-[0.18em] text-white/70 backdrop-blur">{{ $catName }}</div>
                                <div class="flex items-center gap-2">
                                    @if(!empty($item->is_featured))
                                        <div class="inline-flex rounded-full border border-orange-500/30 bg-orange-500/10 px-3 py-1.5 text-[10px] font-extrabold tracking-wide text-orange-300">مميز</div>
                                    @endif
                                    @if($item->media_type === 'youtube' && !empty($item->youtube_video_id))
                                        <div class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-[10px] font-extrabold tracking-wide text-white/75">فيديو</div>
                                    @endif
                                </div>
                            </div>
                            <div class="absolute inset-x-0 bottom-0 p-4">
                                <h3 class="text-lg font-black text-white sm:text-xl">{{ $item->title }}</h3>
                                @if(!empty($item->caption))
                                    <p class="mt-1 text-xs leading-5 text-white/70 line-clamp-2">{{ $item->caption }}</p>
                                @endif
                                <div class="mt-3 inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[11px] font-extrabold text-white transition group-hover:border-orange-500/40 group-hover:bg-orange-500/10">فتح المعاينة</div>
                            </div>
                        </div>
                    </button>
                </article>
            @endforeach
        </div>
        @if($items->count() > 6)
            <div class="mt-8 text-center">
                <button id="loadMoreBtn" type="button" class="inline-flex rounded-full border border-white/15 bg-white/5 px-5 py-2.5 text-xs font-extrabold text-white transition hover:border-orange-500/40 hover:bg-orange-500/10">عرض المزيد</button>
            </div>
        @endif
    @else
        <div class="rounded-[24px] border border-white/10 bg-white/5 p-7 text-center text-sm text-white/60">
            لا توجد أعمال منشورة حاليًا. أضف الأعمال من لوحة التحكم لتظهر هنا.
        </div>
    @endif
</section>

{{-- CTA --}}
<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="relative overflow-hidden rounded-[30px] border border-orange-500/20 bg-gradient-to-br from-orange-500/12 via-white/5 to-white/5 p-7 shadow-[0_30px_90px_rgba(0,0,0,0.4)] sm:p-9">
        <div class="absolute -left-24 top-1/2 h-52 w-52 -translate-y-1/2 rounded-full bg-orange-500/10 blur-3xl"></div>
        <div class="absolute -right-16 top-0 h-36 w-36 rounded-full bg-white/5 blur-3xl"></div>
        <div class="relative z-10 mx-auto max-w-3xl text-center">
            <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.25em] text-orange-400">ابدأ الآن</p>
            <h2 class="text-2xl font-black sm:text-3xl">هل لديك فكرة تحتاج تنفيذًا بصريًا أنيقًا؟</h2>
            <p class="mt-4 text-xs leading-7 text-white/70 sm:text-sm">سواء كان مشروعك إعلانًا، فعالية، أو محتوى بصريًا لعلامتك التجارية، يمكننا تحويله إلى عمل يليق بالصورة التي تريد تقديمها.</p>
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="/booking" class="inline-flex rounded-full bg-orange-500 px-6 py-3 text-xs font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.35)]">احجز الآن</a>
                <a href="/services" class="inline-flex rounded-full border border-white/15 bg-white/5 px-6 py-3 text-xs font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">اكتشف الخدمات</a>
            </div>
        </div>
    </div>
</section>

{{-- VIEWER (موحد للصور + يوتيوب + ريلز) --}}
<div id="portfolioViewer" class="fixed inset-0 z-[999] hidden bg-black/95 backdrop-blur-sm" aria-hidden="true">
    <div class="absolute inset-0 flex flex-col">
        <div class="flex items-center justify-between px-4 md:px-8 py-4 border-b border-white/10">
            <div class="text-sm uppercase tracking-[0.2em] text-white/45">معرض الأعمال</div>
            <button id="closeViewer" type="button"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-full border border-white/15 text-white hover:bg-white hover:text-black transition">✕</button>
        </div>
        <div class="flex-1 relative overflow-hidden">
            <button id="prevItem" type="button"
                    class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition">&#8592;</button>
            <button id="nextItem" type="button"
                    class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition">&#8594;</button>
            <div class="h-full max-w-7xl mx-auto px-6 md:px-10 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1.4fr)_380px] gap-8 items-center">
                <div class="relative rounded-[2rem] overflow-hidden border border-white/10 bg-white/5 min-h-[300px] md:min-h-[520px] flex items-center justify-center">
                    <div id="viewerMedia" class="w-full h-full flex items-center justify-center"></div>
                </div>
                <div class="space-y-5">
                    <div class="flex flex-wrap gap-2">
                        <span id="viewerService" class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white border border-white/10"></span>
                        <span id="viewerType" class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white border border-white/10 hidden"></span>
                    </div>
                    <div>
                        <h2 id="viewerTitle" class="text-3xl md:text-4xl font-semibold leading-tight"></h2>
                        <p id="viewerCaption" class="mt-4 text-white/65 leading-relaxed text-base md:text-lg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .reels-carousel::-webkit-scrollbar { display: none; }
    .reels-carousel { -ms-overflow-style: none; }
    .reel-card { transition: opacity 0.4s ease, filter 0.4s ease, transform 0.5s ease; }
    @media (min-width: 640px) {
        .reel-card { width: calc(25% - 12px) !important; }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Reels Infinite Carousel (RTL-aware) ────────────────
    var carousel = document.getElementById('reelsCarousel');
    if (carousel) {
        var isRTL = getComputedStyle(carousel).direction === 'rtl';
        var originalCards = Array.from(carousel.querySelectorAll('.reel-card'));
        var totalOriginals = originalCards.length;
        var needsInfinite = totalOriginals >= 4;

        if (needsInfinite) {
            // Append clones for infinite effect
            originalCards.forEach(function (card) {
                var clone = card.cloneNode(true);
                clone.setAttribute('data-clone', 'true');
                carousel.appendChild(clone);
            });
            carousel.style.scrollSnapType = 'none';
        }
        carousel.style.cursor = 'grab';

        var cardWidth;
        function calcSizes() {
            cardWidth = originalCards[0].offsetWidth + 16;
        }
        calcSizes();

        // RTL scroll helpers
        function getScroll() {
            return isRTL ? -carousel.scrollLeft : carousel.scrollLeft;
        }
        function setScroll(val) {
            carousel.scrollLeft = isRTL ? -val : val;
        }
        function smoothScrollBy(amount) {
            var start = carousel.scrollLeft;
            var target = start + (isRTL ? -amount : amount);
            var duration = 500;
            var steps = 20;
            var i = 0;
            function tick() {
                i++;
                var progress = Math.min(i / steps, 1);
                var ease = progress < 0.5
                    ? 2 * progress * progress
                    : 1 - Math.pow(-2 * progress + 2, 2) / 2;
                carousel.scrollLeft = start + (target - start) * ease;
                if (i < steps) setTimeout(tick, duration / steps);
            }
            tick();
        }

        // Auto-scroll
        var isUserInteracting = false;
        var resumeTimeout = null;
        var maxScroll = carousel.scrollWidth - carousel.clientWidth;

        function autoScroll() {
            setInterval(function () {
                if (isUserInteracting) return;
                maxScroll = carousel.scrollWidth - carousel.clientWidth;
                if (getScroll() >= maxScroll - 20) {
                    carousel.style.scrollBehavior = 'auto';
                    setScroll(0);
                    carousel.style.scrollBehavior = '';
                } else {
                    smoothScrollBy(cardWidth);
                }
            }, 3500);
        }

        function pauseAuto() {
            isUserInteracting = true;
            clearTimeout(resumeTimeout);
            resumeTimeout = setTimeout(function () {
                isUserInteracting = false;
            }, 5000);
        }

        // Mouse drag
        var isDown = false, startX, scrollStart;
        carousel.addEventListener('mousedown', function (e) {
            isDown = true;
            pauseAuto();
            carousel.style.cursor = 'grabbing';
            startX = e.pageX;
            scrollStart = carousel.scrollLeft;
        });
        document.addEventListener('mouseup', function () {
            if (!isDown) return;
            isDown = false;
            carousel.style.cursor = 'grab';
        });
        document.addEventListener('mousemove', function (e) {
            if (!isDown) return;
            e.preventDefault();
            var diff = e.pageX - startX;
            // In RTL, drag direction is naturally handled by the browser
            carousel.scrollLeft = scrollStart - diff;
        });

        // Touch pause
        carousel.addEventListener('touchstart', pauseAuto);

        // Opacity for side cards
        function updateReelOpacity() {
            var rect = carousel.getBoundingClientRect();
            carousel.querySelectorAll('.reel-card').forEach(function (card) {
                var cr = card.getBoundingClientRect();
                var visibleLeft  = Math.max(cr.left, rect.left);
                var visibleRight = Math.min(cr.right, rect.right);
                var visibleWidth = visibleRight - visibleLeft;
                var ratio = Math.max(0, visibleWidth / cr.width);
                card.style.opacity = ratio < 0.8 ? '0.4' : '1';
                card.style.filter  = ratio < 0.8 ? 'brightness(0.5)' : '';
            });
        }
        carousel.addEventListener('scroll', updateReelOpacity);
        updateReelOpacity();
        window.addEventListener('resize', function () { calcSizes(); updateReelOpacity(); });

        // Click handler for reels (originals + clones)
        carousel.addEventListener('click', function (e) {
            var btn = e.target.closest('.reel-open-btn');
            if (!btn) return;
            e.stopPropagation();
            var reelBtns = Array.from(carousel.querySelectorAll('.reel-open-btn:not([data-clone])'));
            var title = btn.dataset.title;
            var matchIdx = 0;
            reelBtns.forEach(function (b, i) { if (b.dataset.title === title) matchIdx = i; });
            allViewerItems = reelBtns.map(function (b) {
                return {
                    mediaType: 'reel', youtubeId: b.dataset.youtubeId, image: '',
                    videoPath: b.dataset.videoPath, reelSource: b.dataset.reelSource,
                    title: b.dataset.title, caption: b.dataset.caption,
                    serviceLabel: b.dataset.cat, cat: b.dataset.cat,
                };
            });
            currentIndex = matchIdx;
            openViewer(allViewerItems[currentIndex]);
        });

        if (needsInfinite) {
            autoScroll();
        }
    }

    // ── Viewer (موحد) ────────────────────────────────────
    const viewer      = document.getElementById('portfolioViewer');
    const closeBtn    = document.getElementById('closeViewer');
    const viewerMedia = document.getElementById('viewerMedia');
    const viewerTitle = document.getElementById('viewerTitle');
    const viewerCap   = document.getElementById('viewerCaption');
    const viewerSvc   = document.getElementById('viewerService');
    const viewerType  = document.getElementById('viewerType');
    const prevBtn     = document.getElementById('prevItem');
    const nextBtn     = document.getElementById('nextItem');

    let allViewerItems = [];
    let currentIndex   = 0;

    function buildMedia(data) {
        const { mediaType, youtubeId, image, videoPath, reelSource } = data;

        if (reelSource === 'mp4' && videoPath) {
            viewerType.textContent = 'Reels';
            viewerType.classList.remove('hidden');
            return '<video controls autoplay playsinline class="max-h-[80vh] w-auto mx-auto rounded-xl"><source src="' + videoPath + '" type="video/mp4"></video>';
        }

        if ((reelSource === 'youtube' || mediaType === 'youtube') && youtubeId) {
            viewerType.textContent = reelSource === 'youtube' ? 'Reels' : 'فيديو';
            viewerType.classList.remove('hidden');
            return '<iframe src="https://www.youtube.com/embed/' + youtubeId + '?autoplay=1" class="w-full h-full min-h-[300px] md:min-h-[480px]" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
        }

        if (image) {
            viewerType.classList.add('hidden');
            return '<img src="' + image + '" alt="' + (data.title || '') + '" class="max-h-[80vh] w-full object-contain">';
        }

        return '<div class="text-white/40 text-sm p-8">لا توجد معاينة</div>';
    }

    function openViewer(data) {
        viewerTitle.textContent = data.title   || '';
        viewerCap.textContent   = data.caption || '';
        viewerSvc.textContent   = data.cat     || data.serviceLabel || '';
        viewerMedia.innerHTML   = buildMedia(data);
        viewer.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeViewer() {
        viewer.classList.add('hidden');
        document.body.style.overflow = '';
        viewerMedia.innerHTML = '';
    }

    // أزرار الأعمال العادية
    document.querySelectorAll('.portfolio-open-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cards = document.querySelectorAll('#portfolioGrid .portfolio-card');
            var gridCards = Array.from(cards).filter(function (c) { return !c.classList.contains('hidden'); });
            allViewerItems = gridCards.map(function (c) {
                return {
                    mediaType:    c.dataset.mediaType,
                    youtubeId:    c.dataset.youtubeId,
                    image:        c.dataset.image,
                    videoPath:    c.dataset.videoPath || '',
                    reelSource:   '',
                    title:        c.dataset.title,
                    caption:      c.dataset.caption,
                    serviceLabel: c.dataset.serviceLabel,
                    cat:          c.dataset.serviceLabel,
                };
            });
            var card = this.closest('.portfolio-card');
            currentIndex = gridCards.indexOf(card);
            if (currentIndex === -1) currentIndex = 0;
            openViewer(allViewerItems[currentIndex]);
        });
    });

    // أزرار الريلز — handled by carousel event delegation above

    closeBtn?.addEventListener('click', closeViewer);
    viewer?.addEventListener('click', function (e) { if (e.target === viewer) closeViewer(); });

    nextBtn?.addEventListener('click', function () {
        currentIndex = (currentIndex + 1) % allViewerItems.length;
        openViewer(allViewerItems[currentIndex]);
    });
    prevBtn?.addEventListener('click', function () {
        currentIndex = (currentIndex - 1 + allViewerItems.length) % allViewerItems.length;
        openViewer(allViewerItems[currentIndex]);
    });

    document.addEventListener('keydown', function (e) {
        if (viewer.classList.contains('hidden')) return;
        if (e.key === 'Escape')     closeViewer();
        if (e.key === 'ArrowLeft')  { currentIndex = (currentIndex + 1) % allViewerItems.length; openViewer(allViewerItems[currentIndex]); }
        if (e.key === 'ArrowRight') { currentIndex = (currentIndex - 1 + allViewerItems.length) % allViewerItems.length; openViewer(allViewerItems[currentIndex]); }
    });

});
</script>
@endpush

@endsection