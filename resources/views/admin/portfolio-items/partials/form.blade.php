@php
    $portfolioItem    = $portfolioItem ?? null;
    $currentMediaType = old('media_type', optional($portfolioItem)->media_type ?? 'image');
    $services         = $services ?? collect();
    $categories       = $categories ?? collect();
    $currentServiceId = old('service_id', optional($portfolioItem)->service_id);
    $currentService   = $currentServiceId ? $services->firstWhere('id', (int) $currentServiceId) : null;
    $currentCategoryId = old('category_id', optional($portfolioItem)->category_id ?? $currentService?->category_id);
    $servicesPayload  = $services->map(fn ($s) => [
        'id'          => (int) $s->id,
        'name'        => (string) $s->name,
        'category_id' => $s->category_id !== null ? (int) $s->category_id : null,
    ])->values()->all();

    $currentIsReel  = old('is_reel',     optional($portfolioItem)->is_reel     ?? false);
    $currentReelSrc = old('reel_source', optional($portfolioItem)->reel_source ?? 'mp4');
    $currentReelUrl = old('reel_url',    optional($portfolioItem)->reel_url    ?? '');
@endphp

<div class="portfolio-form-body">

    {{-- ══════════════════════════════════
         القسم 1 — البيانات الأساسية
    ══════════════════════════════════ --}}
    <section class="pf-section">
        <div class="pf-section-head">
            <div>
                <div class="pf-section-kicker">البيانات الأساسية</div>
                <h2 class="pf-section-title">معلومات العمل</h2>
                <p class="pf-section-text">العنوان، ربط التصنيف والخدمة.</p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6">
                <label class="pf-label">العنوان</label>
                <input type="text" name="title" class="pf-input"
                       value="{{ old('title', optional($portfolioItem)->title ?? '') }}" required>
            </div>

            <div class="col-span-12 md:col-span-6" id="portfolio-category-service-root">
                <label class="pf-label">التصنيف</label>
                <select id="portfolio_category_filter" name="category_id" class="pf-input" autocomplete="off">
                    <option value="">— اختر التصنيف —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) $currentCategoryId === (string) $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="pf-error">{{ $message }}</div>@enderror

                <label class="pf-label mt-3">الخدمة (اختياري)</label>
                <select name="service_id" id="portfolio_service_id" class="pf-input" autocomplete="off">
                    <option value="">— بدون ربط —</option>
                    @if($currentService)
                        <option value="{{ $currentService->id }}" selected>{{ $currentService->name }}</option>
                    @endif
                </select>
                @error('service_id')<div class="pf-error">{{ $message }}</div>@enderror
                <div class="pf-help">إن لم تظهر خدمات، تأكد أن كل خدمة مربوطة بتصنيف.</div>
            </div>
        </div>
    </section>

    <script type="application/json" id="portfolio-services-payload">
{!! json_encode($servicesPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!}
    </script>

    {{-- ══════════════════════════════════
         القسم 2 — الوسائط
    ══════════════════════════════════ --}}
    <section class="pf-section">
        <div class="pf-section-head">
            <div>
                <div class="pf-section-kicker">الوسائط</div>
                <h2 class="pf-section-title">نوع المحتوى</h2>
                <p class="pf-section-text">اختر نوع الوسائط ثم أدخل المحتوى المناسب.</p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 items-start">

            <div class="col-span-12 md:col-span-4">
                <label class="pf-label">نوع الوسائط</label>
                <select name="media_type" id="media_type" class="pf-input">
                    <option value="image"   {{ $currentMediaType === 'image'   ? 'selected' : '' }}>🖼️ صورة</option>
                    <option value="youtube" {{ $currentMediaType === 'youtube' ? 'selected' : '' }}>▶️ YouTube</option>
                    <option value="reel"    {{ $currentMediaType === 'reel'    ? 'selected' : '' }}>🎬 ريل (فيديو قصير)</option>
                </select>
            </div>

            {{-- صورة --}}
            <div class="col-span-12 md:col-span-8 media-field media-image">
                <label class="pf-label block">رفع الصورة</label>
                <div class="pf-upload-card">
                    <input type="file" name="image" id="image" class="hidden"
                           accept="image/jpeg,image/png,image/webp,image/gif">
                    @if(!empty(optional($portfolioItem)->image_path))
                        <label for="image" class="pf-upload-box hidden" id="image-upload-label">
                            <div class="pf-upload-icon">🖼️</div>
                            <div class="pf-upload-title">اختر صورة</div>
                            <div class="pf-upload-text">JPG / PNG / WebP / GIF — حتى 10 ميجابايت</div>
                            <div class="pf-upload-btn">اختيار صورة</div>
                            <div id="image-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                        </label>
                        <div id="image-selected-preview" class="pf-media-live-preview">
                            <img id="image-selected-img" src="{{ asset(optional($portfolioItem)->image_path) }}" alt="preview" class="pf-image-preview">
                            <label for="image" class="pf-change-btn">تغيير الصورة</label>
                        </div>
                    @else
                        <label for="image" class="pf-upload-box" id="image-upload-label">
                            <div class="pf-upload-icon">🖼️</div>
                            <div class="pf-upload-title">اختر صورة</div>
                            <div class="pf-upload-text">JPG / PNG / WebP / GIF — حتى 10 ميجابايت</div>
                            <div class="pf-upload-btn">اختيار صورة</div>
                            <div id="image-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                        </label>
                        <div id="image-selected-preview" class="pf-media-live-preview hidden">
                            <img id="image-selected-img" src="" alt="preview" class="pf-image-preview">
                            <label for="image" class="pf-change-btn">تغيير الصورة</label>
                        </div>
                    @endif
                </div>
                @error('image')<div class="pf-error">{{ $message }}</div>@enderror
            </div>

            {{-- YouTube --}}
            <div class="col-span-12 md:col-span-8 media-field media-youtube">
                <div class="pf-youtube-card">
                    <label class="pf-label">رابط YouTube</label>
                    <input type="text" name="youtube_url" class="pf-input"
                           value="{{ old('youtube_url', optional($portfolioItem)->youtube_url ?? '') }}"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <div class="pf-help">رابط عادي أو Shorts أو youtu.be</div>
                    @error('youtube_url')<div class="pf-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ريل --}}
            <div class="col-span-12 md:col-span-8 media-field media-reel">
                <div class="grid grid-cols-12 gap-3">

                    <div class="col-span-12 md:col-span-5">
                        <label class="pf-label">مصدر الريل</label>
                        <select name="reel_source" id="reel_source" class="pf-input">
                            <option value="mp4"     {{ $currentReelSrc === 'mp4'     ? 'selected' : '' }}>رفع فيديو (mp4)</option>
                            <option value="youtube" {{ $currentReelSrc === 'youtube' ? 'selected' : '' }}>YouTube Shorts</option>
                        </select>
                    </div>

                    <div class="col-span-12 md:col-span-7" id="reel-url-field">
                        <label class="pf-label">رابط الريل</label>
                        <input type="text" name="reel_url" class="pf-input"
                               value="{{ $currentReelUrl }}"
                               placeholder="https://www.instagram.com/reel/...">
                        <div class="pf-help">الصق رابط الريل من المنصة المختارة</div>
                        @error('reel_url')<div class="pf-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-span-12 hidden" id="reel-mp4-field">
                        <label class="pf-label block">رفع فيديو mp4</label>
                        <div class="pf-upload-card">
                            <input type="file" name="video" id="video" class="hidden" accept="video/mp4,video/webm">
                            @if(!empty(optional($portfolioItem)->video_path))
                                <label for="video" class="pf-upload-box hidden" id="video-upload-label">
                                    <div class="pf-upload-icon">🎬</div>
                                    <div class="pf-upload-title">اختر ملف فيديو</div>
                                    <div class="pf-upload-text">MP4 / WebM — حتى 100 ميجابايت</div>
                                    <div class="pf-upload-btn">اختيار فيديو</div>
                                    <div id="video-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                                </label>
                                <div id="video-selected-preview" class="pf-media-live-preview">
                                    <video src="{{ asset(optional($portfolioItem)->video_path) }}" class="pf-video-preview" controls muted></video>
                                    <label for="video" class="pf-change-btn">تغيير الفيديو</label>
                                </div>
                            @else
                                <label for="video" class="pf-upload-box" id="video-upload-label">
                                    <div class="pf-upload-icon">🎬</div>
                                    <div class="pf-upload-title">اختر ملف فيديو</div>
                                    <div class="pf-upload-text">MP4 / WebM — حتى 100 ميجابايت</div>
                                    <div class="pf-upload-btn">اختيار فيديو</div>
                                    <div id="video-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                                </label>
                                <div id="video-selected-preview" class="pf-media-live-preview hidden">
                                    <video id="video-selected-el" class="pf-video-preview" controls muted></video>
                                    <label for="video" class="pf-change-btn">تغيير الفيديو</label>
                                </div>
                            @endif
                        </div>
                        @error('video')<div class="pf-error">{{ $message }}</div>@enderror
                    </div>

                    {{-- صورة مصغرة للريل --}}
                    <div class="col-span-12">
                        <label class="pf-label block">صورة مصغرة (Thumbnail)</label>
                        <div class="pf-upload-card">
                            <input type="file" name="image" id="reel-thumbnail" class="hidden"
                                   accept="image/jpeg,image/png,image/webp,image/gif">
                            @if(!empty(optional($portfolioItem)->image_path))
                                <label for="reel-thumbnail" class="pf-upload-box hidden" id="reel-thumb-upload-label">
                                    <div class="pf-upload-icon">🖼️</div>
                                    <div class="pf-upload-title">اختر صورة مصغرة</div>
                                    <div class="pf-upload-text">JPG / PNG / WebP / GIF — حتى 10 ميجابايت</div>
                                    <div class="pf-upload-btn">اختيار صورة</div>
                                    <div id="reel-thumb-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                                </label>
                                <div id="reel-thumb-selected-preview" class="pf-media-live-preview">
                                    <img id="reel-thumb-selected-img" src="{{ asset(optional($portfolioItem)->image_path) }}" alt="thumbnail" class="pf-image-preview">
                                    <label for="reel-thumbnail" class="pf-change-btn">تغيير الصورة المصغرة</label>
                                </div>
                            @else
                                <label for="reel-thumbnail" class="pf-upload-box" id="reel-thumb-upload-label">
                                    <div class="pf-upload-icon">🖼️</div>
                                    <div class="pf-upload-title">اختر صورة مصغرة</div>
                                    <div class="pf-upload-text">JPG / PNG / WebP / GIF — حتى 10 ميجابايت</div>
                                    <div class="pf-upload-btn">اختيار صورة</div>
                                    <div id="reel-thumb-file-name" class="pf-upload-file-name">لم يتم اختيار ملف بعد</div>
                                </label>
                                <div id="reel-thumb-selected-preview" class="pf-media-live-preview hidden">
                                    <img id="reel-thumb-selected-img" src="" alt="thumbnail" class="pf-image-preview">
                                    <label for="reel-thumbnail" class="pf-change-btn">تغيير الصورة المصغرة</label>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Checkbox أظهر ضمن الـ 4 الثابتة --}}
                    <div class="col-span-12 mt-2">
                        <label class="pf-check-card" style="max-width:420px; border-color:#e85d0044; background:rgba(232,93,0,0.04)">
                            <input type="checkbox" name="is_reel" id="is_reel" value="1"
                                   {{ $currentIsReel ? 'checked' : '' }}>
                            <span>
                                <strong>أظهر ضمن الـ 4 الثابتة في صفحة الأعمال</strong>
                                <small>يظهر هذا الريل في قسم الفيديوهات القصيرة (الموقع يعرض 4 فقط بالأولوية)</small>
                            </span>
                        </label>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════
         القسم 3 — الظهور
    ══════════════════════════════════ --}}
    <section class="pf-section">
        <div class="pf-section-head">
            <div>
                <div class="pf-section-kicker">الظهور</div>
                <h2 class="pf-section-title">الترتيب والحالة</h2>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-4">
                <label class="pf-label">الترتيب</label>
                <input type="number" min="0" name="sort_order" class="pf-input"
                       value="{{ old('sort_order', optional($portfolioItem)->sort_order ?? 0) }}">
            </div>

            <div class="col-span-12 md:col-span-4 flex items-end">
                <label class="pf-check-card w-full">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', optional($portfolioItem)->is_featured ?? false) ? 'checked' : '' }}>
                    <span>
                        <strong>مميز</strong>
                        <small>ظهور في الـ Hero</small>
                    </span>
                </label>
            </div>

            <div class="col-span-12 md:col-span-4 flex items-end">
                <label class="pf-check-card w-full">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', optional($portfolioItem)->is_active ?? true) ? 'checked' : '' }}>
                    <span>
                        <strong>نشط</strong>
                        <small>يظهر في الموقع</small>
                    </span>
                </label>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<style>
/* ── Live Preview ─────────────────────────────── */
.pf-media-live-preview{
    margin-top:14px;
    border:1px solid #f1f5f9;
    border-radius:16px;
    background:linear-gradient(135deg,#fafafa 0%,#f8fafc 100%);
    padding:14px;
    animation:pfFadeSlide .35s ease;
}
.pf-video-preview{
    width:100%;
    max-height:340px;
    border-radius:14px;
    background:#000;
    object-fit:contain;
}

/* ── Upload Progress Overlay ─────────────────── */
.pf-progress-overlay{
    position:fixed;
    inset:0;
    z-index:9999;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(0,0,0,.55);
    backdrop-filter:blur(6px);
    animation:pfFadeIn .25s ease;
}
.pf-progress-card{
    width:420px;
    max-width:90vw;
    background:#fff;
    border-radius:24px;
    padding:36px 32px;
    text-align:center;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}
.pf-progress-title{
    font-size:18px;
    font-weight:900;
    color:#0f172a;
    margin-bottom:4px;
}
.pf-progress-subtitle{
    font-size:13px;
    color:#64748b;
    margin-bottom:22px;
}
.pf-progress-track{
    position:relative;
    height:10px;
    background:#f1f5f9;
    border-radius:999px;
    overflow:hidden;
}
.pf-progress-bar{
    height:100%;
    width:0%;
    background:linear-gradient(90deg,#f97316,#fb923c);
    border-radius:999px;
    transition:width .3s ease;
}
.pf-progress-percent{
    margin-top:14px;
    font-size:32px;
    font-weight:900;
    color:#f97316;
    letter-spacing:-1px;
}
.pf-progress-size{
    font-size:12px;
    color:#94a3b8;
    margin-top:4px;
}
.pf-progress-done .pf-progress-bar{
    background:linear-gradient(90deg,#22c55e,#4ade80);
}
.pf-progress-done .pf-progress-percent{
    color:#22c55e;
}

/* ── Change button ─────────────────────────────── */
.pf-change-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-top:10px;
    padding:8px 18px;
    border-radius:999px;
    background:#f1f5f9;
    color:#475569;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    transition:.2s ease;
    border:1px solid #e2e8f0;
}
.pf-change-btn:hover{
    background:#f97316;
    color:#fff;
    border-color:#f97316;
}

@keyframes pfFadeIn{from{opacity:0}to{opacity:1}}
@keyframes pfFadeSlide{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── معاينة الصورة الرئيسية (تحل مكان صندوق الرفع) ──
    (function(){
        const input    = document.getElementById('image');
        const label    = document.getElementById('image-upload-label');
        const preview  = document.getElementById('image-selected-preview');
        const img      = document.getElementById('image-selected-img');
        if (!input) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            img.src = url;
            label.classList.add('hidden');
            preview.classList.remove('hidden');
        });
    })();

    // ── معاينة فيديو الريل ────────────────────────
    (function(){
        const input   = document.getElementById('video');
        const label   = document.getElementById('video-upload-label');
        const preview = document.getElementById('video-selected-preview');
        const videoEl = document.getElementById('video-selected-el') || preview?.querySelector('video');
        if (!input) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file || !videoEl) return;
            videoEl.src = URL.createObjectURL(file);
            videoEl.load();
            if (label) label.classList.add('hidden');
            preview.classList.remove('hidden');
        });
    })();

    // ── معاينة الصورة المصغرة للريل ───────────────
    (function(){
        const input    = document.getElementById('reel-thumbnail');
        const label    = document.getElementById('reel-thumb-upload-label');
        const preview  = document.getElementById('reel-thumb-selected-preview');
        const img      = document.getElementById('reel-thumb-selected-img');
        if (!input) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            img.src = URL.createObjectURL(file);
            if (label) label.classList.add('hidden');
            preview.classList.remove('hidden');
        });
    })();


    // ═══════════════════════════════════════════════
    //  Upload Progress — رفع بنسبة تقدم
    // ═══════════════════════════════════════════════
    document.querySelectorAll('form[enctype="multipart/form-data"]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            // تحقق إن كان هناك ملف فعلي
            const hasFile = Array.from(form.querySelectorAll('input[type="file"]')).some(f => f.files.length > 0);
            if (!hasFile) return; // لا ملفات = submit عادي بدون progress

            e.preventDefault();

            // بناء الـ overlay
            const overlay = document.createElement('div');
            overlay.className = 'pf-progress-overlay';
            overlay.innerHTML = `
                <div class="pf-progress-card">
                    <div class="pf-progress-title">جاري رفع الملفات...</div>
                    <div class="pf-progress-subtitle">يرجى عدم إغلاق الصفحة</div>
                    <div class="pf-progress-track">
                        <div class="pf-progress-bar" id="pf-bar"></div>
                    </div>
                    <div class="pf-progress-percent" id="pf-percent">0%</div>
                    <div class="pf-progress-size" id="pf-size"></div>
                </div>
            `;
            document.body.appendChild(overlay);

            const bar     = document.getElementById('pf-bar');
            const percent = document.getElementById('pf-percent');
            const sizeEl  = document.getElementById('pf-size');
            const card    = overlay.querySelector('.pf-progress-card');

            // تعطيل زر الإرسال
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            // إرسال عبر XMLHttpRequest
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action, true);

            // Accept HTML حتى يعامل Laravel الطلب كطلب عادي
            xhr.setRequestHeader('Accept', 'text/html');

            // تقدم الرفع
            xhr.upload.addEventListener('progress', function (ev) {
                if (!ev.lengthComputable) return;
                const pct = Math.round((ev.loaded / ev.total) * 100);
                bar.style.width = pct + '%';
                percent.textContent = pct + '%';

                const loadedMB = (ev.loaded / 1024 / 1024).toFixed(1);
                const totalMB  = (ev.total  / 1024 / 1024).toFixed(1);
                sizeEl.textContent = loadedMB + ' MB / ' + totalMB + ' MB';
            });

            xhr.addEventListener('load', function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    bar.style.width = '100%';
                    percent.textContent = '100%';
                    card.classList.add('pf-progress-done');

                    const titleEl = overlay.querySelector('.pf-progress-title');
                    const subEl   = overlay.querySelector('.pf-progress-subtitle');
                    titleEl.textContent = 'تم الرفع بنجاح!';
                    subEl.textContent   = 'جاري التوجيه...';

                    setTimeout(function () {
                        if (xhr.responseURL) {
                            window.location.href = xhr.responseURL;
                        } else {
                            window.location.reload();
                        }
                    }, 600);
                } else {
                    // خطأ في الـ validation أو غيره
                    overlay.remove();
                    if (submitBtn) submitBtn.disabled = false;
                    // إعادة عرض الصفحة مع الأخطاء
                    document.open();
                    document.write(xhr.responseText);
                    document.close();
                }
            });

            xhr.addEventListener('error', function () {
                overlay.remove();
                if (submitBtn) submitBtn.disabled = false;
                alert('حدث خطأ أثناء الرفع. يرجى المحاولة مرة أخرى.');
            });

            xhr.send(formData);
        });
    });


    // ═══════════════════════════════════════════════
    //  media_type toggle + تعطيل حقول الملفات المخفية
    // ═══════════════════════════════════════════════
    const mediaSelect = document.getElementById('media_type');
    function toggleMedia() {
        const val = mediaSelect?.value;
        document.querySelectorAll('.media-field').forEach(function(el) {
            el.classList.add('hidden');
            // تعطيل حقول الملفات المخفية حتى لا تُرسل فارغة
            el.querySelectorAll('input[type="file"]').forEach(function(inp) { inp.disabled = true; });
        });
        const active = document.querySelector('.media-' + val);
        if (active) {
            active.classList.remove('hidden');
            active.querySelectorAll('input[type="file"]').forEach(function(inp) { inp.disabled = false; });
        }
    }
    mediaSelect?.addEventListener('change', toggleMedia);
    toggleMedia();


    // ═══════════════════════════════════════════════
    //  reel_source toggle
    // ═══════════════════════════════════════════════
    const reelSource   = document.getElementById('reel_source');
    const reelUrlField = document.getElementById('reel-url-field');
    const reelMp4Field = document.getElementById('reel-mp4-field');
    const placeholders = {
        youtube:   'https://youtube.com/shorts/...',
        instagram: 'https://www.instagram.com/reel/...',
        tiktok:    'https://www.tiktok.com/@.../video/...',
    };
    function toggleReelSource() {
        const val = reelSource?.value;
        if (val === 'mp4') {
            reelUrlField?.classList.add('hidden');
            reelMp4Field?.classList.remove('hidden');
        } else {
            reelUrlField?.classList.remove('hidden');
            reelMp4Field?.classList.add('hidden');
            const urlInput = reelUrlField?.querySelector('input[name="reel_url"]');
            if (urlInput && placeholders[val]) urlInput.placeholder = placeholders[val];
        }
    }
    reelSource?.addEventListener('change', toggleReelSource);
    toggleReelSource();


    // ═══════════════════════════════════════════════
    //  category → services filter
    // ═══════════════════════════════════════════════
    const catSelect    = document.getElementById('portfolio_category_filter');
    const svcSelect    = document.getElementById('portfolio_service_id');
    const servicesData = JSON.parse(document.getElementById('portfolio-services-payload')?.textContent || '[]');
    const currentSvcId = {{ old('service_id', optional($portfolioItem)->service_id ?? 'null') }};

    function filterServices(catId) {
        const filtered = catId
            ? servicesData.filter(s => String(s.category_id) === String(catId))
            : servicesData;
        svcSelect.innerHTML = '<option value="">— بدون ربط —</option>';
        filtered.forEach(s => {
            const opt = document.createElement('option');
            opt.value       = s.id;
            opt.textContent = s.name;
            if (currentSvcId && String(s.id) === String(currentSvcId)) opt.selected = true;
            svcSelect.appendChild(opt);
        });
    }
    catSelect?.addEventListener('change', function () { filterServices(this.value); });
    filterServices(catSelect?.value);

});
</script>
@endpush