@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">الباقات</h1>
        <div class="db-page-subtitle">إدارة الباقات والخيارات المتاحة لكل خدمة.</div>
    </div>
    <a class="db-btn-primary" href="{{ route('admin.packages.create') }}">
        <i class="fas fa-plus"></i>
        إضافة باقة
    </a>
</div>

@if(session('success'))
    <div class="db-alert db-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="db-alert db-alert-danger">{{ session('error') }}</div>
@endif

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الباقة</th>
                        <th>الخدمة المرتبطة</th>
                        <th>السعر</th>
                        <th>خيارات</th>
                        <th>قابلة للبناء</th>
                        <th>مفعلة</th>
                        <th>ترتيب</th>
                        <th width="200">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $pkg)
                    <tr>
                        <td>{{ $pkg->id }}</td>
                        <td><strong>{{ $pkg->name }}</strong>
                            @if($pkg->is_featured) <span class="db-badge db-badge-new ms-1">مميزة</span> @endif
                        </td>
                        <td>{{ $pkg->service->name ?? '—' }}</td>
                        <td>{{ number_format($pkg->price) }} د.ج</td>
                        <td>
                            <a href="{{ route('admin.packages.options.index', $pkg) }}" class="db-icon-btn db-edit-btn">
                                خيارات ({{ $pkg->options()->count() }})
                            </a>
                        </td>
                        <td>
                            {!! $pkg->is_buildable ? '<span class="db-badge db-badge-completed">نعم</span>' : '<span class="db-badge">لا</span>' !!}
                        </td>
                        <td>
                            {!! $pkg->is_active ? '<span class="db-badge db-badge-completed">نعم</span>' : '<span class="db-badge">لا</span>' !!}
                        </td>
                        <td>{{ $pkg->sort_order }}</td>
                        <td>
                            {{-- تعديل --}}
                            <a class="db-icon-btn db-edit-btn"
                               href="{{ route('admin.packages.edit', $pkg) }}"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- حذف --}}
                            <form action="{{ route('admin.packages.destroy', $pkg) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('حذف الباقة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-[var(--tx-muted)] text-center py-4">لا توجد باقات حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $packages->links() }}</div>
    </div>
</div>
@endsection
