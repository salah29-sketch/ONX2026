@extends('layouts.admin')

@section('content')
@php
    $statusList = [
        'pending'     => ['label' => 'قيد الانتظار', 'class' => 'db-badge-new'],
        'unconfirmed' => ['label' => 'غير مؤكد', 'class' => 'db-badge-new'],
        'confirmed'   => ['label' => 'مؤكد', 'class' => 'db-badge-confirmed'],
        'assigned'    => ['label' => 'تم التعيين', 'class' => 'db-badge-confirmed'],
        'in_progress' => ['label' => 'قيد التنفيذ', 'class' => 'db-badge-progress'],
        'completed'   => ['label' => 'مكتمل', 'class' => 'db-badge-completed'],
        'cancelled'   => ['label' => 'ملغى', 'class' => 'db-badge-cancelled'],
    ];
@endphp

<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تفاصيل الحجز #{{ $booking->id }}</h1>
        <div class="db-page-subtitle">عرض بيانات الحجز وتحديث حالته.</div>
    </div>

    <a href="{{ route('admin.bookings.index') }}" class="db-btn-secondary">
        <i class="fas fa-arrow-right"></i>
        رجوع
    </a>
</div>

{{-- ─── بيانات الحجز: عرض افتراضي + أيقونة تعديل ───── --}}
<div x-data="{ openDetails: true, openPhotos: true, openClientPhotos: true }">
<div class="db-card mb-4">
    <div class="db-card-header db-card-header-toggle flex justify-between items-center" @click="openDetails = !openDetails" role="button" :aria-expanded="openDetails">
        <span>بيانات الحجز</span>
        <span class="flex items-center" onclick="event.stopPropagation();">
            <button type="button" class="text-sm border-0 me-2 text-[var(--onx-orange)] hover:text-[var(--onx-orange-hover)] transition js-booking-edit-toggle" title="تعديل" id="btn-booking-edit">
                <i class="fas fa-pen"></i>
            </button>
            <i class="fas fa-chevron-down db-collapse-icon"></i>
        </span>
    </div>
    <div x-show="openDetails" x-collapse class="db-card-body">
        {{-- وضع العرض (افتراضي) ─── --}}
        <div id="booking-details-view" class="db-detail-grid">
            <div class="db-detail-item">
                <div class="db-detail-label">العميل</div>
                <div class="db-detail-value">
                    @if($booking->client)
                        <a href="{{ route('admin.clients.show', $booking->client->id) }}">{{ $booking->client->name }}</a>
                    @else — @endif
                </div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">الهاتف</div>
                <div class="db-detail-value">{{ $booking->phone ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">البريد الإلكتروني</div>
                <div class="db-detail-value">{{ $booking->email ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">نوع الخدمة</div>
                <div class="db-detail-value">{{ $booking->service?->name ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">الباقة المختارة</div>
                <div class="db-detail-value">{{ $booking->package?->name ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">السعر الإجمالي (DA)</div>
                <div class="db-detail-value">{{ $booking->final_price ? number_format($booking->final_price, 0) : ($booking->total_price ? number_format($booking->total_price, 0) : '—') }}</div>
            </div>
            @if($booking->service?->slug === 'events')
                <div class="db-detail-item">
                    <div class="db-detail-label">تاريخ الحفل</div>
                    <div class="db-detail-value">{{ $booking->event_date?->format('Y-m-d') ?? '—' }}</div>
                </div>
                <div class="db-detail-item">
                    <div class="db-detail-label">مكان الحفل</div>
                    <div class="db-detail-value">{{ $booking->eventBooking?->venueName() ?? '—' }}</div>
                </div>
            @else
                <div class="db-detail-item">
                    <div class="db-detail-label">التاريخ</div>
                    <div class="db-detail-value">{{ $booking->event_date?->format('Y-m-d') ?? '—' }}</div>
                </div>
            @endif
            <div class="db-detail-item">
                <div class="db-detail-label">اسم النشاط التجاري</div>
                <div class="db-detail-value">{{ $booking->business_name ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">الميزانية</div>
                <div class="db-detail-value">{{ $booking->budget ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">موعد التسليم</div>
                <div class="db-detail-value">{{ $booking->deadline ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">الحالة</div>
                <div class="db-detail-value">{{ $statusList[$booking->status->value ?? $booking->status]['label'] ?? $booking->status }}</div>
            </div>
            <div class="db-detail-item" style="grid-column:1/-1;">
                <div class="db-detail-label">الملاحظات</div>
                <div class="db-detail-value">{{ $booking->notes ? nl2br(e($booking->notes)) : '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">تاريخ الإنشاء</div>
                <div class="db-detail-value">{{ $booking->created_at?->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="db-detail-item">
                <div class="db-detail-label">آخر تحديث</div>
                <div class="db-detail-value">{{ $booking->updated_at?->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
        </div>

        {{-- وضع التعديل (مخفي حتى النقر على أيقونة التعديل) ─── --}}
        <div id="booking-details-edit" class="hidden">
            <form action="{{ route('admin.bookings.updateDetails', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="db-detail-grid">
                    <div class="db-detail-item">
                        <div class="db-detail-label">العميل</div>
                        <div class="db-detail-value">
                            @if($booking->client)
                                <a href="{{ route('admin.clients.show', $booking->client->id) }}">{{ $booking->client->name }}</a>
                            @else — @endif
                        </div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">الهاتف</div>
                        <div class="db-detail-value">{{ $booking->phone ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">البريد الإلكتروني</div>
                        <div class="db-detail-value">{{ $booking->email ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">نوع الخدمة</div>
                        <div class="db-detail-value">{{ $booking->service?->name ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">الباقة المختارة</div>
                        <div class="db-detail-value">
                            @if($packagesByService && $packagesByService->isNotEmpty())
                                <select name="package_id" class="db-input">
                                    <option value="">— اختر —</option>
                                    @foreach($packagesByService as $id => $name)
                                        <option value="{{ $id }}" {{ (string)$booking->package_id === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            @else
                                {{ $booking->package?->name ?? '—' }}
                            @endif
                        </div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">السعر الإجمالي (DA)</div>
                        <div class="db-detail-value">
                            <input type="number" name="total_price" value="{{ $booking->total_price }}" min="0" step="1" placeholder="0" class="db-input">
                        </div>
                    </div>
                    @if($booking->service?->slug === 'events')
                        <div class="db-detail-item">
                            <div class="db-detail-label">تاريخ الحفل</div>
                            <div class="db-detail-value">
                                <input type="date" name="event_date" value="{{ $booking->event_date?->format('Y-m-d') }}" class="db-input">
                            </div>
                        </div>
                    @endif
                    <div class="db-detail-item">
                        <div class="db-detail-label">اسم النشاط التجاري</div>
                        <div class="db-detail-value">{{ $booking->business_name ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">الميزانية</div>
                        <div class="db-detail-value">{{ $booking->budget ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">موعد التسليم</div>
                        <div class="db-detail-value">{{ $booking->deadline ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">الحالة</div>
                        <div class="db-detail-value">
                            <select name="status" class="db-input">
                                @foreach($statusList as $value => $info)
                                    <option value="{{ $value }}" {{ ($booking->status->value ?? $booking->status) === $value ? 'selected' : '' }}>{{ $info['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="db-detail-item" style="grid-column:1/-1;">
                        <div class="db-detail-label">الملاحظات</div>
                        <div class="db-detail-value">
                            <textarea name="notes" class="db-input" rows="3">{{ $booking->notes }}</textarea>
                        </div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">تاريخ الإنشاء</div>
                        <div class="db-detail-value">{{ $booking->created_at?->format('Y-m-d H:i') ?? '—' }}</div>
                    </div>
                    <div class="db-detail-item">
                        <div class="db-detail-label">آخر تحديث</div>
                        <div class="db-detail-value">{{ $booking->updated_at?->format('Y-m-d H:i') ?? '—' }}</div>
                    </div>
                </div>
                <div class="db-form-actions mt-3">
                    <button type="submit" class="db-btn-success">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <button type="button" class="db-btn-secondary js-booking-edit-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── المدفوعات والملفات ──────────────────────────── --}}
@include('admin.bookings._payments-files', ['booking' => $booking])

{{-- ─── صور الحجز ───────────────────────────────────── --}}
<div class="db-card mt-4">
    <div class="db-card-header db-card-header-toggle flex justify-between items-center" @click="openPhotos = !openPhotos" role="button" :aria-expanded="openPhotos">
        <span>صور الحجز (العميل يشاهدها ويختار حتى 200 مميزة للطباعة)</span>
        <i class="fas fa-chevron-down db-collapse-icon"></i>
    </div>
    <div x-show="openPhotos" x-collapse class="db-card-body">
        @if($errors->has('post_size'))
            <div class="alert-danger mb-3">
                <strong>خطأ في الرفع:</strong> {{ $errors->first('post_size') }}
            </div>
        @endif
        {{-- منطقة الرفع ─── --}}
        <div class="booking-photos-upload mb-4">
            <form action="{{ route('admin.bookings.photos.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 flex-wrap items-end">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="mb-4 me-3 mb-2">
                    <label class="db-label mb-1 block">رفع صور</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="db-input">
                </div>
                <button type="submit" class="db-btn-primary mb-2">
                    <i class="fas fa-cloud-upload-alt"></i> رفع
                </button>
            </form>
        </div>

        @if($photosPaginated->isNotEmpty())
            <div class="booking-photos-meta mb-3 flex justify-between items-center flex-wrap gap-2">
                <span class="text-muted">
                    <i class="fas fa-images"></i>
                    إجمالي الصور: <strong>{{ $photosPaginated->total() }}</strong>
                    @if($photosPaginated->total() > $photosPaginated->count())
                        (عرض {{ $photosPaginated->count() }} في هذه الصفحة)
                    @endif
                </span>
            </div>

            <script type="application/json" id="booking-lightbox-sources">{{ json_encode($photosPaginated->pluck('path')->map(function($path) { return asset($path); })->values()) }}</script>
            {{-- Lightbox مثل عميل DB: معرض الصور، أسهم دائرية فقط، شريط علوي ─── --}}
            <div id="booking-photo-lightbox" class="booking-lightbox" aria-hidden="true">
                <div class="booking-lightbox-backdrop"></div>
                <div class="booking-lightbox-topbar">
                    <span class="booking-lightbox-title">معرض الصور</span>
                    <button type="button" class="booking-lightbox-close" title="إغلاق" aria-label="إغلاق">&times;</button>
                </div>
                <button type="button" class="booking-lightbox-prev" title="السابقة" aria-label="السابقة">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button type="button" class="booking-lightbox-next" title="التالية" aria-label="التالية">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="booking-lightbox-content">
                    <img src="" alt="تكبير" class="booking-lightbox-img">
                </div>
                <div class="booking-lightbox-counter"></div>
            </div>

            <div class="booking-photos-gallery row">
                @foreach($photosPaginated as $p)
                    <div class="col-span-6 sm:col-span-4 lg:col-span-2 mb-3">
                        <div class="booking-photo-card">
                            <a href="{{ asset($p->path) }}" class="booking-photo-link js-booking-lightbox-open" title="تكبير في الصفحة">
                                <img src="{{ asset($p->path) }}" alt="صورة حجز" loading="lazy" class="booking-photo-img">
                            </a>
                            <div class="booking-photo-actions">
                                <a href="{{ asset($p->path) }}" class="photo-action-btn js-booking-lightbox-open" title="تكبير">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <form action="{{ route('admin.bookings.photos.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('حذف هذه الصورة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="db-btn-danger text-sm" title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($photosPaginated->hasPages())
                <div class="flex justify-center mt-4">
                    {{ $photosPaginated->links() }}
                </div>
            @endif
        @else
            <div class="booking-photos-empty text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3" style="opacity:0.5;"></i>
                <p class="text-muted mb-0">لا توجد صور بعد. ارفع صور الحجز من الأعلى.</p>
            </div>
        @endif
    </div>
</div>

{{-- ─── الصور المميزة التي اختارها العميل ─────────── --}}
@if($booking->client && $clientSelectedPhotos->isNotEmpty())
<div class="db-card mt-4 border-success">
    <div class="db-card-header db-card-header-toggle bg-green-600 text-white flex justify-between items-center" @click="openClientPhotos = !openClientPhotos" role="button" :aria-expanded="openClientPhotos">
        <span>ما اختاره العميل للطباعة ({{ $clientSelectedPhotos->count() }})</span>
        <i class="fas fa-chevron-down db-collapse-icon"></i>
    </div>
    <div x-show="openClientPhotos" x-collapse class="db-card-body">
        <div class="grid grid-cols-12 gap-4">
            @foreach($clientSelectedPhotos as $p)
                <div class="col-span-12 md:col-span-2 col-4 mb-2">
                    <a href="{{ asset($p->path) }}" target="_blank">
                        <img src="{{ asset($p->path) }}" alt="مختار"
                             class="img-fluid rounded border border-success">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@section('styles')
<style>
.db-card-header-toggle { cursor: pointer; }
.db-card-header-toggle:hover { opacity: 0.9; }
.db-collapse-icon { transition: transform 0.2s ease; }
.db-card-header-toggle[aria-expanded="false"] .db-collapse-icon { transform: rotate(-90deg); }
.js-booking-edit-toggle { cursor: pointer; }

/* معرض صور الحجز */
.booking-photos-upload {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}
.booking-photos-gallery { margin-bottom: 0; }
.booking-photo-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.booking-photo-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    transform: translateY(-2px);
}
.booking-photo-link { display: block; aspect-ratio: 1; }
.booking-photo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}
.booking-photo-card:hover .booking-photo-img { transform: scale(1.05); }
.booking-photo-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 8px;
    background: linear-gradient(to top, rgba(0,0,0,.75), transparent);
    display: flex;
    justify-content: center;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.booking-photo-card:hover .booking-photo-actions { opacity: 1; }
.booking-photo-actions .btn { padding: 4px 10px; }
.booking-photo-actions .photo-action-btn { color: #334155; }
.booking-photos-empty { background: #f8fafc; border-radius: 12px; }

/* Lightbox مثل عميل DB: خلفية سوداء، شريط علوي، أسهم دائرية فقط */
.booking-lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.95);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
    display: flex;
    flex-direction: column;
}
.booking-lightbox.is-open {
    opacity: 1;
    visibility: visible;
}
body.booking-lightbox-active { overflow: hidden; }
.booking-lightbox-backdrop {
    position: absolute;
    inset: 0;
    cursor: pointer;
    z-index: 0;
}
.booking-lightbox-topbar {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.booking-lightbox-title {
    font-size: 0.875rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
}
.booking-lightbox-close {
    width: 2.75rem;
    height: 2.75rem;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    background: transparent;
    color: #fff;
    font-size: 1.25rem;
    line-height: 1;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.booking-lightbox-close:hover {
    background: #fff;
    color: #000;
}
/* أسهم دائرية فقط (بدون صور مصغرة) — مثل عميل DB */
.booking-lightbox-prev,
.booking-lightbox-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    width: 3rem;
    height: 3rem;
    padding: 0;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    background: rgba(0,0,0,0.4);
    color: #f97316;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, transform 0.2s;
}
.booking-lightbox-prev:hover,
.booking-lightbox-next:hover {
    background: #fff;
    color: #000;
    transform: translateY(-50%) scale(1.1);
}
.booking-lightbox-prev { right: 1.5rem; }
.booking-lightbox-next { left: 1.5rem; }
.booking-lightbox-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    min-height: 0;
    position: relative;
    z-index: 1;
}
.booking-lightbox-img {
    max-width: 100%;
    max-height: 70vh;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 1rem;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
}
.booking-lightbox-counter {
    position: absolute;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    padding: 0.4rem 0.9rem;
    background: rgba(0,0,0,0.5);
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 999px;
}
</style>
@endsection

@section('scripts')
<script>
(function() {
    var viewEl = document.getElementById('booking-details-view');
    var editEl = document.getElementById('booking-details-edit');
    var btnEdit = document.querySelector('.js-booking-edit-toggle');
    var btnCancel = document.querySelector('.js-booking-edit-cancel');
    if (!viewEl || !editEl || !btnEdit) return;
    function showView() {
        viewEl.classList.remove('hidden');
        editEl.classList.add('hidden');
    }
    function showEdit() {
        viewEl.classList.add('hidden');
        editEl.classList.remove('hidden');
    }
    btnEdit.addEventListener('click', showEdit);
    if (btnCancel) btnCancel.addEventListener('click', showView);
})();

(function() {
    var lb = document.getElementById('booking-photo-lightbox');
    var lbImg = lb && lb.querySelector('.booking-lightbox-img');
    var lbBackdrop = lb && lb.querySelector('.booking-lightbox-backdrop');
    var lbClose = lb && lb.querySelector('.booking-lightbox-close');
    var lbPrev = lb && lb.querySelector('.booking-lightbox-prev');
    var lbNext = lb && lb.querySelector('.booking-lightbox-next');
    var lbCounter = lb && lb.querySelector('.booking-lightbox-counter');
    if (!lb || !lbImg) return;

    var sources = [];
    var sourcesEl = document.getElementById('booking-lightbox-sources');
    if (sourcesEl && sourcesEl.textContent) {
        try { sources = JSON.parse(sourcesEl.textContent); } catch (err) {}
    }
    if (sources.length === 0) {
        document.querySelectorAll('.booking-photo-link.js-booking-lightbox-open').forEach(function(a) {
            var href = a.getAttribute('href');
            if (href) sources.push(href);
        });
    }
    var currentIndex = 0;

    function showImage(index) {
        if (sources.length === 0) return;
        currentIndex = (index + sources.length) % sources.length;
        lbImg.src = sources[currentIndex];
        if (lbCounter) lbCounter.textContent = (currentIndex + 1) + ' / ' + sources.length;
    }
    function openLightbox(src) {
        var idx = sources.indexOf(src);
        if (idx === -1) idx = 0;
        currentIndex = idx;
        lbImg.src = sources[currentIndex];
        if (lbCounter) lbCounter.textContent = (currentIndex + 1) + ' / ' + sources.length;
        lb.classList.add('is-open');
        document.body.classList.add('booking-lightbox-active');
    }
    function closeLightbox() {
        lb.classList.remove('is-open');
        document.body.classList.remove('booking-lightbox-active');
    }
    function goPrev(e) {
        if (e) e.stopPropagation();
        if (sources.length) showImage(currentIndex - 1);
    }
    function goNext(e) {
        if (e) e.stopPropagation();
        if (sources.length) showImage(currentIndex + 1);
    }

    document.querySelectorAll('.js-booking-lightbox-open').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var src = el.getAttribute('href');
            if (src) openLightbox(src);
        });
    });
    if (lbBackdrop) lbBackdrop.addEventListener('click', closeLightbox);
    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', goPrev);
    if (lbNext) lbNext.addEventListener('click', goNext);
    document.addEventListener('keydown', function(e) {
        if (!lb.classList.contains('is-open')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') goNext(e);
        if (e.key === 'ArrowLeft') goPrev(e);
    });
})();
</script>
</div>{{-- /x-data --}}
@endsection
