@extends('client.layout')

@section('client_content')
<div class="mb-6 project-photos-page">
    <a href="{{ route('client.project-photos') }}" class="text-sm font-bold text-amber-600 hover:underline">← صور مشروعي</a>
</div>
<h2 class="mb-2 text-xl font-black text-gray-800">صور الحجز #{{ $booking->id }}</h2>
<p class="mb-2 text-sm text-gray-600">اضغط على القلب لاختيار الصورة كمميزة للطباعة (الحد الأقصى 200 صورة). يمكنك المشاهدة والتحميل.</p>
<p class="mb-4 text-xs text-gray-500">اضغط على أي صورة لفتح <strong>المعرض</strong> (عرض كبير + التنقل بين الصور).</p>

<p class="portal-selected-count mb-4 rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-bold text-amber-800"><span dir="ltr">{{ $selectedCount }} / 200</span> المختار</p>

@php
    $photoUrls = $photos->isEmpty() ? [] : $photos->map(fn($p) => asset($p->path))->values()->all();
    $photoIds = $photos->isEmpty() ? [] : $photos->pluck('id')->values()->all();
@endphp
@if($photos->isEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-500 shadow-sm">لا توجد صور لهذا الحجز بعد.</div>
@else
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4" id="photos-grid">
        @foreach($photos as $i => $p)
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm" data-photo-id="{{ $p->id }}">
                <button type="button" class="block w-full aspect-square overflow-hidden text-start lightbox-open" data-index="{{ $i }}">
                    <img src="{{ asset($p->path) }}" alt="صورة" class="h-full w-full object-cover cursor-zoom-in">
                </button>
                <button type="button"
                        class="absolute bottom-2 right-2 rounded-full bg-black/60 p-2 text-lg transition hover:bg-orange-500/80 photo-fav"
                        data-id="{{ $p->id }}"
                        title="{{ in_array($p->id, $selectedIds) ? 'إلغاء التمييز' : 'اختيار كمميزة' }}">
                    @if(in_array($p->id, $selectedIds))
                        <span class="text-red-400">❤️</span>
                    @else
                        <span class="text-white/60">🤍</span>
                    @endif
                </button>
                <a href="{{ asset($p->path) }}" download class="absolute bottom-2 left-2 rounded-full bg-black/60 px-2 py-1.5 text-xs font-bold text-white/90 hover:bg-orange-500/80">تحميل</a>
            </div>
        @endforeach
    </div>

    {{-- المعرض — نفس تصميم portfolio: شريط علوي، صورة كبيرة، لوحة جانبية، أسهم برتقالية --}}
    <div id="lightbox" class="fixed inset-0 z-[100] hidden bg-black/95 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="معرض الصور">
        <div id="lightbox-backdrop" class="absolute inset-0 z-0 cursor-pointer" aria-hidden="true" title="إغلاق"></div>
        <div class="absolute inset-0 flex flex-col">
            {{-- شريط علوي (مثل portfolio: عنوان يمين، X يسار في RTL) --}}
            <div class="flex items-center justify-between px-4 md:px-8 py-4 border-b border-white/10 relative z-10">
                <div class="text-sm uppercase tracking-[0.2em] text-white/45">معرض الصور</div>
                <button type="button" id="lightbox-close" class="inline-flex items-center justify-center w-11 h-11 rounded-full border border-white/15 text-white hover:bg-white hover:text-black transition" aria-label="إغلاق">✕</button>
            </div>
            {{-- المحتوى: صورة + لوحة جانبية (نفس grid portfolio) --}}
            <div class="flex-1 relative overflow-hidden min-h-0">
                <button type="button" id="lightbox-prev" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition" aria-label="السابق">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="lightbox-next" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-white/15 bg-black/40 text-orange-500 flex items-center justify-center hover:bg-white hover:text-black hover:scale-110 transition" aria-label="التالي">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="h-full max-w-7xl mx-auto px-6 md:px-10 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1.4fr)_380px] gap-8 items-center relative z-10">
                    {{-- عمود الصورة (مثل viewerMedia) --}}
                    <div class="relative rounded-[2rem] overflow-hidden border border-white/10 bg-white/5 min-h-[300px] md:min-h-[520px] flex items-center justify-center">
                        <img id="lightbox-img" src="" alt="معرض" class="w-full h-full object-contain max-h-[70vh] select-none">
                    </div>
                    {{-- لوحة جانبية (مثل viewerService + viewerTitle + زر احجز) --}}
                    <div class="space-y-5">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white border border-white/10">صور الحجز #{{ $booking->id }}</span>
                        </div>
                        <div>
                            <h2 id="lightbox-counter" class="text-3xl md:text-4xl font-semibold leading-tight text-white"></h2>
                            <p class="mt-4 text-white/65 leading-relaxed text-base md:text-lg">اختر الصورة كمميزة للطباعة أو حمّلها.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="button" id="lightbox-like" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/15 bg-white/5 px-5 py-3 text-sm font-bold text-white hover:border-orange-500/40 hover:bg-orange-500/10 transition" title="اختيار كمميزة للطباعة">
                                <span id="lightbox-heart">🤍</span>
                                <span>إعجاب</span>
                            </button>
                            <a id="lightbox-download" href="" download class="inline-flex items-center justify-center gap-2 rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black hover:bg-orange-400 transition" title="تحميل الصورة">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                تحميل
                            </a>
                        </div>
                        <a href="{{ route('client.review.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-orange-500/40 bg-orange-500/10 px-5 py-3 text-sm font-bold text-orange-300 hover:bg-orange-500/20 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            أضف تقييمك أو رأيك
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
(function() {
    var urls = @json($photoUrls);
    var photoIds = @json($photoIds);
    var selectedIds = @json($selectedIds);
    if (!urls || urls.length === 0) return;
    var lightbox = document.getElementById('lightbox');
    if (!lightbox) return;
    var lightboxImg = document.getElementById('lightbox-img');
    var lightboxCounter = document.getElementById('lightbox-counter');
    var lightboxLike = document.getElementById('lightbox-like');
    var lightboxHeart = document.getElementById('lightbox-heart');
    var lightboxDownload = document.getElementById('lightbox-download');
    var index = 0;

    function isSelected(photoId) {
        return selectedIds && selectedIds.indexOf(photoId) !== -1;
    }
    function updateLightboxHeart() {
        var id = photoIds[index];
        if (!id) return;
        if (isSelected(id)) {
            lightboxHeart.textContent = '❤️';
            lightboxHeart.classList.add('text-red-400');
            lightboxHeart.classList.remove('text-white/70');
        } else {
            lightboxHeart.textContent = '🤍';
            lightboxHeart.classList.remove('text-red-400');
            lightboxHeart.classList.add('text-white/70');
        }
    }

    function showLightbox(i) {
        index = (i + urls.length) % urls.length;
        lightboxImg.src = urls[index];
        lightboxCounter.textContent = 'الصورة ' + (index + 1) + ' من ' + urls.length;
        lightboxDownload.href = urls[index];
        lightboxDownload.download = '';
        updateLightboxHeart();
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function hideLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.lightbox-open').forEach(function(btn) {
        btn.addEventListener('click', function() { showLightbox(parseInt(this.getAttribute('data-index'), 10)); });
    });
    document.getElementById('lightbox-close').addEventListener('click', hideLightbox);
    document.getElementById('lightbox-backdrop').addEventListener('click', hideLightbox);
    document.getElementById('lightbox-prev').addEventListener('click', function(e) {
        e.stopPropagation();
        index = (index - 1 + urls.length) % urls.length;
        showLightbox(index);
    });
    document.getElementById('lightbox-next').addEventListener('click', function(e) {
        e.stopPropagation();
        index = (index + 1) % urls.length;
        showLightbox(index);
    });

    lightboxLike.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var id = photoIds[index];
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
                        selectedIds = selectedIds.filter(function(x) { return x !== id; });
                    }
                    updateLightboxHeart();
                    var gridHeart = document.querySelector('.photo-fav[data-id="' + id + '"] span');
                    if (gridHeart) {
                        gridHeart.textContent = data.selected ? '❤️' : '🤍';
                        gridHeart.classList.toggle('text-red-400', data.selected);
                        gridHeart.classList.toggle('text-white/60', !data.selected);
                    }
                    var counter = document.querySelector('.portal-selected-count');
                    if (counter) counter.innerHTML = '<span dir="ltr">' + data.count + ' / 200</span> المختار';
                } else if (data.message) alert(data.message);
            });
    });

    if (urls.length <= 1) {
        document.getElementById('lightbox-prev').style.display = 'none';
        document.getElementById('lightbox-next').style.display = 'none';
    }
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('hidden')) {
            if (e.key === 'Escape') hideLightbox();
            else if (e.key === 'ArrowRight') document.getElementById('lightbox-prev').click();
            else if (e.key === 'ArrowLeft') document.getElementById('lightbox-next').click();
        }
    });
})();

document.querySelectorAll('.photo-fav').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var id = this.dataset.id;
        var heart = this.querySelector('span');
        var formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('booking_photo_id', id);
        fetch('{{ route("client.project-photos.toggle") }}', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(function(data) {
                if (data.ok) {
                    if (data.selected) { heart.textContent = '❤️'; heart.classList.remove('text-white/60'); heart.classList.add('text-red-400'); }
                    else { heart.textContent = '🤍'; heart.classList.add('text-white/60'); heart.classList.remove('text-red-400'); }
                    var counter = document.querySelector('.portal-selected-count');
                    if (counter) counter.innerHTML = '<span dir="ltr">' + data.count + ' / 200</span> المختار';
                } else if (data.message) alert(data.message);
            });
    });
});
</script>
@endsection
