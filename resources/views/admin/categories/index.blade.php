@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">التصنيفات</h1>
        <div class="db-page-subtitle">تجميع الخدمات حسب التصنيف.</div>
    </div>
    <a class="db-btn-primary" href="{{ route('admin.categories.create') }}">
        <i class="fas fa-plus"></i>
        إضافة تصنيف
    </a>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الأيقونة</th>
                        <th>الاسم</th>
                        <th>Slug</th>
                        <th>خدمات</th>
                        <th>نشط</th>
                        <th>ترتيب</th>
                        <th width="140"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $cat->id }}</td>
                        <td>{{ $cat->icon }}</td>
                        <td><strong>{{ $cat->name }}</strong></td>
                        <td><code>{{ $cat->slug }}</code></td>
                        <td>{{ $cat->services_count ?? $cat->services()->count() }}</td>
                        <td>
                            @if($cat->is_active)
                                <span class="db-badge db-badge-completed">نعم</span>
                            @else
                                <span class="db-badge db-badge-secondary">لا</span>
                            @endif
                        </td>
                        <td>{{ $cat->sort_order }}</td>
                        <td>
                            <a class="db-icon-btn db-edit-btn" href="{{ route('admin.categories.edit', $cat) }}" title="تعديل"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('حذف التصنيف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-muted text-center py-4">لا توجد تصنيفات. <a href="{{ route('admin.categories.create') }}">إضافة تصنيف</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
