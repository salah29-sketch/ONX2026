@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تفاصيل العمل</h1>
        <div class="db-page-subtitle">عرض كامل لبيانات عنصر Portfolio.</div>
    </div>

    <a href="{{ route('admin.portfolio-items.index') }}" class="db-btn-secondary">
        رجوع
    </a>
</div>

<div class="db-card">
    <div class="db-card-header">بيانات العمل</div>

    <div class="db-card-body">
        @php
            $thumb = null;

            if ($portfolioItem->media_type === 'youtube' && $portfolioItem->youtube_video_id) {
                $thumb = 'https://img.youtube.com/vi/' . $portfolioItem->youtube_video_id . '/hqdefault.jpg';
            } elseif ($portfolioItem->image_path) {
                $thumb = asset($portfolioItem->image_path);
            }
        @endphp

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-4 mb-4">
                @if($thumb)
                    <img src="{{ $thumb }}" alt="{{ $portfolioItem->title }}" class="max-w-full h-auto rounded" style="max-height:260px;object-fit:cover;">
                @else
                    <div class="border rounded p-4 text-center text-[var(--tx-muted)]">
                        لا توجد معاينة
                    </div>
                @endif
            </div>

            <div class="col-span-12 md:col-span-8">
                <table class="db-table">
                    <tr>
                        <th>العنوان</th>
                        <td>{{ $portfolioItem->title }}</td>
                    </tr>
                    <tr>
                        <th>تصنيف الخدمة</th>
                        <td>{{ $portfolioItem->service?->category?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>الخدمة المرتبطة</th>
                        <td>{{ $portfolioItem->service?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>نوع العرض (تلقائي)</th>
                        <td>{{ $portfolioItem->service_type ?: '—' }} <span class="text-[var(--tx-muted)] small">(من slug التصنيف)</span></td>
                    </tr>
                    <tr>
                        <th>نوع الوسائط</th>
                        <td>{{ $portfolioItem->media_type }}</td>
                    </tr>
                    <tr>
                        <th>مميز</th>
                        <td>{{ $portfolioItem->is_featured ? 'نعم' : 'لا' }}</td>
                    </tr>
                    <tr>
                        <th>نشط</th>
                        <td>{{ $portfolioItem->is_active ? 'نعم' : 'لا' }}</td>
                    </tr>
                    <tr>
                        <th>الترتيب</th>
                        <td>{{ $portfolioItem->sort_order }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ النشر</th>
                        <td>{{ $portfolioItem->published_at ? $portfolioItem->published_at->format('Y-m-d H:i') : '—' }}</td>
                    </tr>

                    @if($portfolioItem->media_type === 'image')
                        <tr>
                            <th>مسار الصورة</th>
                            <td>{{ $portfolioItem->image_path ?: '—' }}</td>
                        </tr>
                    @endif

                    @if($portfolioItem->media_type === 'youtube')
                        <tr>
                            <th>رابط YouTube</th>
                            <td>
                                @if($portfolioItem->youtube_url)
                                    <a href="{{ $portfolioItem->youtube_url }}" target="_blank">فتح الفيديو</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Video ID</th>
                            <td>{{ $portfolioItem->youtube_video_id ?: '—' }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>أماكن الظهور</th>
                        <td>
                            @forelse($portfolioItem->placements as $placement)
                                <span class="db-badge db-badge-info">{{ $placement->placement_key }}</span>
                            @empty
                                —
                            @endforelse
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
