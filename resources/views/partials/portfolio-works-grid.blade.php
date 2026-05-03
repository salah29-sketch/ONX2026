@php
    $items = $items ?? collect();
    $sectionTitle = $sectionTitle ?? 'نماذج من الأعمال';
    $sectionSubtitle = $sectionSubtitle ?? 'WORKS';
    $sectionDescription = $sectionDescription ?? '';
    $badgeText = $badgeText ?? 'WORK';
@endphp

@if(isset($items) && $items->count())
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-3 text-sm font-extrabold uppercase tracking-[0.25em] text-orange-400">
                {{ $sectionSubtitle }}
            </p>

            <h2 class="text-3xl font-black sm:text-4xl">
                {{ $sectionTitle }}
            </h2>

            @if(!empty($sectionDescription))
                <p class="mt-4 max-w-2xl text-sm leading-8 text-white/65 sm:text-base">
                    {{ $sectionDescription }}
                </p>
            @endif
        </div>

        <a href="/portfolio"
           class="inline-flex w-fit rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-extrabold text-white/80 transition hover:border-orange-500/40 hover:bg-orange-500/10 hover:text-white">
            شاهد المزيد
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($items->take(3) as $item)
            @php
                $coverImage = null;

                if ($item->media_type === 'youtube' && !empty($item->youtube_video_id)) {
                    $coverImage = 'https://img.youtube.com/vi/' . $item->youtube_video_id . '/hqdefault.jpg';
                } elseif (!empty($item->image_path)) {
                    $coverImage = asset($item->image_path);
                }
            @endphp

            <div class="group relative overflow-hidden rounded-[28px] border border-white/10 bg-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.35)]">
                <div class="relative h-[420px] w-full overflow-hidden">
                    @if($coverImage)
                        <img
                            src="{{ $coverImage }}"
                            alt="{{ $item->title }}"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-110 {{ $item->media_type === 'youtube' ? 'blur-[1.5px]' : 'scale-[1.03]' }}"
                            draggable="false"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-white/5 text-sm font-bold text-white/40">
                            لا توجد صورة
                        </div>
                    @endif

                    @if($item->media_type === 'youtube' && !empty($item->youtube_video_id))
                        <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]"></div>

                        <button
                            type="button"
                            class="absolute inset-0 z-20 flex items-center justify-center"
                            aria-label="تشغيل الفيديو"
                            data-video-open
                            data-video-id="{{ $item->youtube_video_id }}"
                        >
                            <span class="flex h-24 w-24 items-center justify-center rounded-full bg-black/40 backdrop-blur-md border border-white/20 shadow-[0_20px_40px_rgba(0,0,0,0.45)] transition duration-300 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-10 w-10 text-white ml-1"
                                     viewBox="0 0 24 24"
                                     fill="currentColor">
                                    <path d="M8 5.14v14l11-7-11-7z"/>
                                </svg>
                            </span>
                        </button>
                    @elseif($item->media_type === 'image' && !empty($coverImage))
                        <button
                            type="button"
                            class="absolute inset-0 z-20"
                            aria-label="عرض الصورة"
                            data-image-open
                            data-image-src="{{ $coverImage }}"
                            data-image-alt="{{ $item->title }}"
                        >
                            <span class="sr-only">عرض الصورة</span>
                        </button>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 z-30 p-5">
                        <div class="max-w-[80%]">
                            <div class="text-[10px] font-extrabold tracking-[0.22em] text-orange-400">
                                {{ $badgeText }}
                            </div>

                            <h3 class="mt-2 text-xl font-black text-white sm:text-2xl">
                                {{ $item->title }}
                            </h3>

                            @if(!empty($item->caption))
                                <p class="mt-1 text-sm leading-6 text-white/70">
                                    {{ $item->caption }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

@once
    {{-- Video Modal --}}
    <div id="videoModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 p-4 backdrop-blur-sm">
        <div class="relative w-full max-w-5xl">
            <button
                type="button"
                class="absolute -top-12 left-0 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                aria-label="إغلاق"
                data-video-close
            >
                ✕
            </button>

            <div class="relative overflow-hidden rounded-[24px] border border-white/10 bg-black shadow-[0_20px_60px_rgba(0,0,0,0.45)]" style="padding-top:56.25%;">
                <iframe
                    id="videoFrame"
                    class="absolute inset-0 h-full w-full"
                    src=""
                    title="YouTube video player"
                    frameborder="0"
                    allow="autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/85 p-4 backdrop-blur-sm transition-all duration-300">
        <div class="relative w-full max-w-6xl">
            <div class="absolute left-4 top-4 z-30 flex items-center gap-2 rounded-full border border-white/10 bg-black/35 px-2 py-2 backdrop-blur-md shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
                <button
                    type="button"
                    class="viewer-tool-btn"
                    aria-label="تكبير"
                    title="تكبير"
                    data-image-zoom-in
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                        <path d="M11 8v6"></path>
                        <path d="M8 11h6"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    class="viewer-tool-btn"
                    aria-label="تصغير"
                    title="تصغير"
                    data-image-zoom-out
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                        <path d="M8 11h6"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    class="viewer-tool-btn"
                    aria-label="إعادة"
                    title="إعادة الحجم"
                    data-image-zoom-reset
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                </button>
            </div>

            <button
                type="button"
                class="absolute right-4 top-4 z-30 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-black/35 text-white backdrop-blur-md transition hover:bg-white/15"
                aria-label="إغلاق"
                title="إغلاق"
                data-image-close
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>

            <div id="imageModalPanel" class="overflow-hidden rounded-[28px] border border-white/10 bg-transparent shadow-[0_25px_80px_rgba(0,0,0,0.5)] opacity-0 scale-95 transition-all duration-300">
                <div class="relative flex items-center justify-center overflow-auto bg-transparent p-4">
                    <img
                        id="imageModalSrc"
                        src=""
                        alt=""
                        class="viewer-image block h-auto w-auto max-w-[90vw] max-h-[90vh] select-none"
                        draggable="false"
                    >
                </div>
            </div>
        </div>
    </div>
@endonce