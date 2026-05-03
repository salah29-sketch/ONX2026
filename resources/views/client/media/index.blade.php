@extends('client.layout')

@section('title', 'الميديا - بوابة العملاء')

@push('styles')
<style>
.media-filter { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
.media-filter a { padding: 10px 18px; border-radius: 999px; font-size: 14px; font-weight: 700; text-decoration: none; transition: all .2s; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; }
.media-filter a:hover { border-color: #fcd34d; color: #b45309; background: #fefce8; }
.media-filter a.active { background: rgba(245,158,11,.15); border-color: #f59e0b; color: #b45309; }
.media-section-title { font-size: 1rem; font-weight: 800; color: #374151; margin-bottom: 12px; }
.media-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
.media-gallery .thumb { aspect-ratio: 1; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; background: #f9fafb; position: relative; }
.media-gallery .thumb img { width: 100%; height: 100%; object-fit: cover; }
.media-gallery .thumb .overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.6), transparent 50%); display: flex; align-items: flex-end; justify-content: space-between; padding: 8px; opacity: 0; transition: opacity .2s; pointer-events: none; }
.media-gallery .thumb:hover .overlay { opacity: 1; }
.media-gallery .thumb .overlay a, .media-gallery .thumb .overlay span { color: #fff; font-size: 11px; font-weight: 700; pointer-events: auto; }
.media-gallery .thumb a.thumb-zoom, .media-gallery .thumb button.thumb-zoom { cursor: zoom-in; }
/* معرض الصور — نفس تصميم صفحة صور الحجز */
.media-lightbox-wrap .media-lightbox-backdrop { cursor: pointer; }
.video-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04); margin-bottom: 16px; }
.video-card .poster-wrap { position: relative; aspect-ratio: 16/9; background: #111; }
.video-card .poster-wrap img { width: 100%; height: 100%; object-fit: cover; }
.video-card .poster-wrap video { width: 100%; height: 100%; object-fit: contain; }
.video-card .poster-wrap .play-overlay { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(0,0,0,.35); cursor: pointer; transition: background .2s; }
.video-card .poster-wrap .play-overlay:hover { background: rgba(0,0,0,.5); }
.video-card .poster-wrap .play-overlay i { font-size: 56px; color: rgba(255,255,255,.95); }
.video-card .poster-wrap .play-overlay .play-hint { font-size: 12px; font-weight: 700; color: rgba(255,255,255,.9); margin-top: 8px; }
.video-card .info { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.video-card .info .label { font-weight: 800; color: #1f2937; }
.video-card .info .meta { font-size: 12px; color: #6b7280; }
.video-card .actions { display: flex; gap: 8px; }
.video-card .actions a, .video-card .actions button { padding: 8px 14px; border-radius: 12px; font-size: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .2s; }
.video-card .actions .btn-download { background: #fef3c7; color: #b45309; }
.video-card .actions .btn-download:hover { background: #fde68a; }
.video-card .actions .btn-share { background: #f3f4f6; color: #4b5563; }
.video-card .actions .btn-share:hover { background: #e5e7eb; }
.file-dl-row { display: flex; align-items: center; gap: 16px; padding: 14px 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; margin-bottom: 10px; text-decoration: none; color: inherit; transition: border-color .2s; }
.file-dl-row:hover { border-color: #fcd34d; }
.file-dl-row .icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.file-dl-row .name { font-weight: 800; color: #1f2937; }
.file-dl-row .meta { font-size: 12px; color: #6b7280; }
.empty-media { text-align: center; padding: 48px 24px; color: #6b7280; background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; }
.empty-media .icon { font-size: 48px; margin-bottom: 16px; opacity: .6; }
.media-files-by-order { margin-bottom: 28px; }
.media-files-by-order .order-head { font-size: 1rem; font-weight: 800; color: #1f2937; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #fde68a; display: flex; align-items: center; gap: 8px; }
.media-files-by-order .order-head .order-num { background: rgba(245,158,11,.15); color: #b45309; padding: 4px 12px; border-radius: 999px; }
.media-files-by-order .order-files { padding-right: 4px; }
</style>
@endpush

@section('client_content')
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">🎬 الميديا</h1>
    <p class="mt-1 text-sm text-gray-500">صور وفيديوهات مشاريعك — معرض الصور، تشغيل الفيديو، تحميل ومشاركة</p>
</div>

{{-- فلتر: الكل | صور | فيديوهات --}}
<nav class="media-filter" aria-label="تصفية الميديا">
    <a href="{{ route('client.media', ['filter' => 'all']) }}" class="{{ $filter === 'all' ? 'active' : '' }}">الكل</a>
    <a href="{{ route('client.media', ['filter' => 'images']) }}" class="{{ $filter === 'images' ? 'active' : '' }}">صور</a>
    <a href="{{ route('client.media', ['filter' => 'videos']) }}" class="{{ $filter === 'videos' ? 'active' : '' }}">فيديوهات</a>
</nav>

@php
    $clientOrderMap = $clientOrderMap ?? [];
    $bookingsWithFiles = $bookingsWithFiles ?? collect();
    $hasPhotos = $bookingsWithPhotos->isNotEmpty();
    $hasVideos = $videoFiles->isNotEmpty();
    $hasOtherFiles = $otherFiles->isNotEmpty();
    $hasFilesSection = $bookingsWithFiles->isNotEmpty();
    $showImages = $filter === 'all' || $filter === 'images';
    $showVideos = $filter === 'all' || $filter === 'videos';
    $showFilesSection = ($filter === 'all' || $filter === 'videos') && $hasFilesSection;
@endphp

{{-- معرض الصور (ثامبنيل + ضغط يكبر — نفس تصميم صفحة صور الحجز) --}}
@if($showImages && $hasPhotos)
    @php
        $galleryItems = $galleryItems ?? [];
        $galleryUrls = array_column($galleryItems, 'url');
        $galleryDownloads = array_column($galleryItems, 'download');
        $galleryBookingIds = array_column($galleryItems, 'booking_id');
        $galleryPhotoIds = array_column($galleryItems, 'photo_id');
        $selectedPhotoIds = $selectedPhotoIds ?? [];
    @endphp
    <section class="mb-8">
        <h2 class="media-section-title">🖼️ صور المشاريع</h2>
        <div class="media-gallery" id="media-gallery">
            @php $galleryIndex = 0; @endphp
            @foreach($bookingsWithPhotos as $booking)
                @foreach($booking->photos as $photo)
                    <div class="thumb" data-type="image">
                        <button type="button" class="thumb-zoom block w-full h-full text-start border-0 p-0 bg-transparent media-lightbox-open" data-index="{{ $galleryIndex }}" title="اضغط للتكبير">
                            <img src="{{ asset($photo->path) }}" alt="صورة حجز {{ $booking->id }}" loading="lazy">
                        </button>
                        <div class="overlay">
                            <span>#{{ $booking->id }}</span>
                            <a href="{{ asset($photo->path) }}" download onclick="event.stopPropagation()">تحميل</a>
                        </div>
                    </div>
                    @php $galleryIndex++; @endphp
                @endforeach
            @endforeach
        </div>

        {{-- المعرض — نفس تصميم صفحة صور الحجز: شريط علوي، صورة كبيرة، أسهم، الصورة X من Y، تحميل --}}
        <div id="media-lightbox" class="media-lightbox-wrap fixed inset-0 z-[200] bg-black/95 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="معرض الصور" style="display: none;">
            <div class="media-lightbox-backdrop absolute inset-0 z-0 cursor-pointer" aria-hidden="true" title="إغلاق"></div>
            <div class="absolute inset-0 flex flex-col">
                <div class="flex items-center justify-between px-4 md:px-8 py-4 border-b border-white/10 relative z-10">
                    <div class="text-sm uppercase tracking-[0.2em] text-white/45">معرض الصور</div>
                    <button type="button" id="media-lightbox-close" class="inline-flex items-center justify-center w-11 h-11 rounded-full border border-white/15 text-white hover:bg-white hover:text-black transition" aria-label="إغلاق">✕</button>
                </div>
                <div class="flex-1 relative overflow-hidden min-h-0">
                    <button type="button" id="media-lightbox-prev" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition" aria-label="السابق">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" id="media-lightbox-next" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition" aria-label="التالي">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div class="h-full max-w-7xl mx-auto px-6 md:px-10 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1.4fr)_380px] gap-8 items-center relative z-10">
                        <div class="relative rounded-[2rem] overflow-hidden border border-white/10 bg-white/5 min-h-[300px] md:min-h-[520px] flex items-center justify-center">
                            <img id="media-lightbox-img" src="" alt="معرض" class="w-full h-full object-contain max-h-[70vh] select-none">
                        </div>
                        <div class="space-y-5">
                            <div class="flex flex-wrap gap-2">
                                <span id="media-lightbox-badge" class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white border border-white/10">—</span>
                            </div>
                            <div>
                                <h2 id="media-lightbox-counter" class="text-3xl md:text-4xl font-semibold leading-tight text-white"></h2>
                                <p class="mt-4 text-white/65 leading-relaxed text-base md:text-lg">اختر الصورة كمميزة للطباعة أو حمّلها.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                <button type="button" id="media-lightbox-like" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/15 bg-white/5 px-5 py-3 text-sm font-bold text-white hover:border-orange-500/40 hover:bg-orange-500/10 transition" title="اختيار كمميزة للطباعة">
                                    <span id="media-lightbox-heart">🤍</span>
                                    <span>إعجاب</span>
                                </button>
                                <a id="media-lightbox-download" href="" download class="inline-flex items-center justify-center gap-2 rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black hover:bg-orange-400 transition" title="تحميل الصورة">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    تحميل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@if($showImages && !$hasPhotos && $filter === 'images')
    <div class="empty-media">
        <div class="icon">🖼️</div>
        <p class="font-bold text-gray-700">لا توجد صور بعد</p>
        <p class="mt-2 text-sm">سيتم إضافة صور مشاريعك من إدارة الموقع.</p>
    </div>
@endif

{{-- فيديوهات (قائمة مسطحة — تختفي إذا وُجد قسم الملفات حسب الطلب) --}}
@if($showVideos && $hasVideos && !$showFilesSection)
    <section class="mb-8">
        <h2 class="media-section-title">🎥 الفيديوهات</h2>
        @foreach($videoFiles as $file)
            <div class="video-card" x-data="{ playing: false }">
                <div class="poster-wrap">
                    @if($file->posterUrl())
                        <img x-show="!playing" src="{{ $file->posterUrl() }}" alt="{{ $file->label }}">
                    @else
                        <div x-show="!playing" class="w-full h-full flex items-center justify-center bg-gray-900 text-white/50">
                            <i class="bi bi-camera-video" style="font-size: 48px;"></i>
                        </div>
                    @endif
                    <template x-teleport="body">
                        <div x-show="playing" x-cloak class="fixed inset-0 z-[200] bg-black flex items-center justify-center p-4" @keydown.escape.window="playing = false">
                            <video src="{{ $file->fileUrl() }}" controls autoplay class="max-w-full max-h-full" @ended="playing = false"></video>
                            <button type="button" @click="playing = false" class="absolute top-4 left-4 w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center hover:bg-white/30" aria-label="إغلاق">✕</button>
                        </div>
                    </template>
                    <div x-show="!playing" class="play-overlay" @click="playing = true" aria-label="اضغط للتكبير وتشغيل الفيديو">
                        <i class="bi bi-play-circle-fill"></i>
                        <span class="play-hint">اضغط للتكبير</span>
                    </div>
                </div>
                <div class="info">
                    <div>
                        <div class="label">{{ $file->label }}</div>
                        <div class="meta">حجز #{{ $file->booking->id }} · {{ $file->booking->projectTypeLabel() }} @if($file->created_at) · {{ $file->created_at->format('d/m/Y') }} @endif</div>
                    </div>
                    <div class="actions">
                        <a href="{{ route('client.files.download', $file->id) }}" class="btn-download" download><i class="bi bi-download me-1"></i> تحميل</a>
                        <button type="button" class="btn-share share-link-btn" data-url="{{ route('client.media', ['filter' => 'videos']) }}"><i class="bi bi-share me-1"></i> مشاركة</button>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endif

@if($showVideos && !$hasVideos && !$hasFilesSection && $filter === 'videos')
    <div class="empty-media">
        <div class="icon">🎥</div>
        <p class="font-bold text-gray-700">لا توجد فيديوهات بعد</p>
        <p class="mt-2 text-sm">سيظهر الفيديو النهائي هنا عند رفعه من الفريق.</p>
    </div>
@endif

{{-- الملفات — مقسمة حسب الطلب --}}
@if($showFilesSection)
    <section class="mb-8">
        <h2 class="media-section-title">📁 الملفات</h2>
        <p class="text-sm text-gray-500 mb-4">فيديوهات وملفات التحميل مرتبة حسب الطلب.</p>
        @foreach($bookingsWithFiles as $booking)
            @php $orderNum = $clientOrderMap[$booking->id] ?? $booking->id; @endphp
            <div class="media-files-by-order">
                <div class="order-head">
                    <span class="order-num">الطلب {{ $orderNum }}</span>
                </div>
                <div class="order-files">
                    @foreach($booking->visibleFiles->sortBy(fn($f) => $f->type === 'video' ? 0 : 1)->values() as $file)
                        @if($file->type === 'video')
                            <div class="video-card mb-4" x-data="{ playing: false }">
                                <div class="poster-wrap">
                                    @if($file->posterUrl())
                                        <img x-show="!playing" src="{{ $file->posterUrl() }}" alt="{{ $file->label }}">
                                    @else
                                        <div x-show="!playing" class="w-full h-full flex items-center justify-center bg-gray-900 text-white/50">
                                            <i class="bi bi-camera-video" style="font-size: 48px;"></i>
                                        </div>
                                    @endif
                                    <template x-teleport="body">
                                        <div x-show="playing" x-cloak class="fixed inset-0 z-[200] bg-black flex items-center justify-center p-4" @keydown.escape.window="playing = false">
                                            <video src="{{ $file->fileUrl() }}" controls autoplay class="max-w-full max-h-full" @ended="playing = false"></video>
                                            <button type="button" @click="playing = false" class="absolute top-4 left-4 w-12 h-12 rounded-full bg-white/20 text-white flex items-center justify-center hover:bg-white/30" aria-label="إغلاق">✕</button>
                                        </div>
                                    </template>
                                    <div x-show="!playing" class="play-overlay" @click="playing = true" aria-label="تشغيل الفيديو">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="play-hint">اضغط للتكبير</span>
                                    </div>
                                </div>
                                <div class="info">
                                    <div>
                                        <div class="label">{{ $file->label }}</div>
                                        <div class="meta">{{ $file->created_at?->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="actions">
                                        <a href="{{ route('client.files.download', $file->id) }}" class="btn-download" download><i class="bi bi-download me-1"></i> تحميل</a>
                                        <button type="button" class="btn-share share-link-btn" data-url="{{ route('client.media', ['filter' => 'videos']) }}"><i class="bi bi-share me-1"></i> مشاركة</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('client.files.download', $file->id) }}" class="file-dl-row" download>
                                <div class="icon" style="background: {{ $file->typeColor() }}20; color: {{ $file->typeColor() }};"><i class="bi {{ $file->typeIcon() }}"></i></div>
                                <div class="flex-1 min-w-0">
                                    <div class="name">{{ $file->label }}</div>
                                    <div class="meta">@if($file->size) {{ $file->humanSize() }} · @endif {{ $file->created_at?->format('d/m/Y') }}</div>
                                </div>
                                <i class="bi bi-download text-gray-400"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
@endif

{{-- تحميلات إضافية (قائمة مسطحة) — تبقى للتوافق أو يمكن إزالتها لصالح قسم الملفات أعلاه --}}
@if(($filter === 'all' || $filter === 'videos') && $hasOtherFiles && !$showFilesSection)
    <section class="mb-6">
        <h2 class="media-section-title">📁 تحميلات</h2>
        @foreach($otherFiles as $file)
            <a href="{{ route('client.files.download', $file->id) }}" class="file-dl-row" download>
                <div class="icon" style="background: {{ $file->typeColor() }}20; color: {{ $file->typeColor() }};"><i class="bi {{ $file->typeIcon() }}"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="name">{{ $file->label }}</div>
                    <div class="meta">الطلب {{ $clientOrderMap[$file->booking->id] ?? $file->booking->id }} @if($file->size) · {{ $file->humanSize() }} @endif</div>
                </div>
                <i class="bi bi-download text-gray-400"></i>
            </a>
        @endforeach
    </section>
@endif

@if($filter === 'all' && !$hasPhotos && !$hasVideos && !$hasOtherFiles && !$hasFilesSection)
    <div class="empty-media">
        <div class="icon">🎬</div>
        <p class="font-bold text-gray-700">لا يوجد ميديا بعد</p>
        <p class="mt-2 text-sm">ستظهر صور وفيديوهات مشاريعك هنا بعد رفعها من إدارة الموقع.</p>
    </div>
@endif

@push('scripts')
<script>
function initMediaLightbox() {
    var galleryUrls = @json($galleryUrls ?? []);
    var galleryDownloads = @json($galleryDownloads ?? []);
    var galleryBookingIds = @json($galleryBookingIds ?? []);
    var galleryPhotoIds = @json($galleryPhotoIds ?? []);
    var selectedIds = @json($selectedPhotoIds ?? []);
    if (!galleryUrls || galleryUrls.length === 0) return;
    var box = document.getElementById('media-lightbox');
    if (!box) return;
    var img = document.getElementById('media-lightbox-img');
    var counter = document.getElementById('media-lightbox-counter');
    var badge = document.getElementById('media-lightbox-badge');
    var downloadBtn = document.getElementById('media-lightbox-download');
    var likeBtn = document.getElementById('media-lightbox-like');
    var heartEl = document.getElementById('media-lightbox-heart');
    var prevBtn = document.getElementById('media-lightbox-prev');
    var nextBtn = document.getElementById('media-lightbox-next');
    var closeBtn = document.getElementById('media-lightbox-close');
    var backdrop = box.querySelector('.media-lightbox-backdrop');
    var index = 0;

    function isSelected(photoId) {
        return selectedIds && selectedIds.indexOf(photoId) !== -1;
    }
    function updateMediaLightboxHeart() {
        var id = galleryPhotoIds[index];
        if (!heartEl) return;
        if (!id) { heartEl.textContent = '🤍'; return; }
        if (isSelected(id)) {
            heartEl.textContent = '❤️';
            heartEl.classList.add('text-red-400');
            heartEl.classList.remove('text-white/70');
        } else {
            heartEl.textContent = '🤍';
            heartEl.classList.remove('text-red-400');
            heartEl.classList.add('text-white/70');
        }
    }

    function showMediaLightbox(i) {
        index = (i + galleryUrls.length) % galleryUrls.length;
        img.src = galleryUrls[index];
        counter.textContent = 'الصورة ' + (index + 1) + ' من ' + galleryUrls.length;
        downloadBtn.href = galleryDownloads[index] || galleryUrls[index];
        if (galleryBookingIds[index]) badge.textContent = 'صور الحجز #' + galleryBookingIds[index];
        else badge.textContent = '—';
        updateMediaLightboxHeart();
        box.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function hideMediaLightbox() {
        box.style.display = 'none';
        document.body.style.overflow = '';
    }
    function goPrev() {
        if (galleryUrls.length <= 1) return;
        index = index <= 0 ? galleryUrls.length - 1 : index - 1;
        showMediaLightbox(index);
    }
    function goNext() {
        if (galleryUrls.length <= 1) return;
        index = index >= galleryUrls.length - 1 ? 0 : index + 1;
        showMediaLightbox(index);
    }

    document.querySelectorAll('.media-lightbox-open').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            showMediaLightbox(parseInt(btn.getAttribute('data-index'), 10));
        });
    });
    if (closeBtn) closeBtn.addEventListener('click', hideMediaLightbox);
    if (backdrop) backdrop.addEventListener('click', hideMediaLightbox);
    if (prevBtn) prevBtn.addEventListener('click', goPrev);
    if (nextBtn) nextBtn.addEventListener('click', goNext);
    if (likeBtn && heartEl) {
        likeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = galleryPhotoIds[index];
            if (!id) return;
            var formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('booking_photo_id', id);
            fetch('{{ route("client.project-photos.toggle") }}', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.ok) {
                        if (data.selected) {
                            if (selectedIds.indexOf(id) === -1) selectedIds.push(id);
                        } else {
                            var idx = selectedIds.indexOf(id);
                            if (idx !== -1) selectedIds.splice(idx, 1);
                        }
                        updateMediaLightboxHeart();
                    }
                })
                .catch(function() {});
        });
    }
    document.addEventListener('keydown', function(e) {
        if (!box || box.style.display === 'none') return;
        if (e.key === 'Escape') hideMediaLightbox();
        if (e.key === 'ArrowRight') goPrev();
        if (e.key === 'ArrowLeft') goNext();
    });
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMediaLightbox);
} else {
    initMediaLightbox();
}
document.querySelectorAll('.share-link-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var url = this.getAttribute('data-url');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() { alert('تم نسخ الرابط'); }).catch(function() { window.prompt('انسخ الرابط:', url); });
        } else { window.prompt('انسخ الرابط:', url); }
    });
});
</script>
@endpush
@endsection
