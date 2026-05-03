@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">Portfolio Items</h1>
        <div class="db-page-subtitle">إدارة الأعمال التي تظهر في الموقع.</div>
    </div>

    <a href="{{ route('admin.portfolio-items.create') }}" class="db-btn-primary">
        <i class="fas fa-plus"></i>
        إضافة عمل
    </a>
</div>

{{-- Tabs التصنيفات --}}
<ul class="flex border-b border-[var(--card-border)] mb-0" style="border-bottom: none;">
    @foreach($categories as $cat)
        <li>
            <a class="px-4 py-2 text-sm font-bold text-[var(--tx-secondary)] border-b-2 border-transparent hover:text-[var(--onx-orange)] transition {{ $activeCategory == $cat->id ? 'border-[var(--onx-orange)] text-[var(--onx-orange)]' : '' }}"
               href="{{ route('admin.portfolio-items.index', ['category_id' => $cat->id]) }}">
               @if($cat->icon)
    <span class="ms-1">{{ $cat->icon }}</span>
@endif
                {{ $cat->name }}
            </a>
        </li>
    @endforeach
</ul>

<div class="db-card" style="border-top-right-radius: 0; border-top-left-radius: 0;">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>الوسيط</th>
                        <th>الخدمة</th>
                        <th>الصورة / الفيديو</th>
                        <th>مميز</th>
                        <th>نشط</th>
                        <th>الترتيب</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $thumb = null;
                            if ($item->media_type === 'youtube' && $item->youtube_video_id) {
                                $thumb = 'https://img.youtube.com/vi/' . $item->youtube_video_id . '/hqdefault.jpg';
                            } elseif ($item->image_path) {
                                $thumb = asset($item->image_path);
                            }
                        @endphp
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->media_type }}</td>
                            <td>{{ $item->service?->name ?? '—' }}</td>
                            <td>
                                @if($thumb)
                                    <img src="{{ $thumb }}" alt="{{ $item->title }}"
                                         style="width:80px;height:55px;object-fit:cover;border-radius:8px;">
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($item->is_featured)
                                    <span class="badge bg-warning text-dark">نعم</span>
                                @else
                                    <span class="text-[var(--tx-muted)]">لا</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">مخفي</span>
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <div class="db-actions">
                                    <a href="{{ route('admin.portfolio-items.show', $item->id) }}"
                                       class="db-icon-btn db-view-btn" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.portfolio-items.edit', $item->id) }}"
                                       class="db-icon-btn db-edit-btn" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.portfolio-items.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="db-icon-btn db-delete-btn" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="db-empty">
                                    <i class="fas fa-images"></i>
                                    لا توجد أعمال في هذا التصنيف.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection