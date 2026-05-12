@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">القاعات</h1></div>
    <a href="{{ route('admin.venues.create') }}" class="db-btn-primary">+ إضافة قاعة</a>
</div>
@if(session('success'))
    <div class="db-alert db-alert-success">{{ session('success') }}</div>
@endif
<div class="db-card">
    <div class="db-card-body">
        <table class="db-table text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الاسم</th>
                    <th>الولاية</th>
                    <th>العنوان</th>
                    <th>نشطة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venues as $v)
                <tr>
                    <td>{{ $v->id }}</td>
                    <td>{{ $v->name }}</td>
                    <td>{{ $v->wilaya?->name ?? '—' }}</td>
                    <td>{{ $v->address ?? '—' }}</td>
                    <td>{!! $v->is_active ? '<span class="db-badge db-badge-completed">نعم</span>' : '<span class="db-badge">لا</span>' !!}</td>
                    <td>
                        <a href="{{ route('admin.venues.edit', $v) }}" class="db-icon-btn db-edit-btn"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.venues.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="db-icon-btn db-delete-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">لا توجد قاعات.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection