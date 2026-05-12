@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">مناطق التنقل</h1></div>
    <a href="{{ route('admin.travel-zones.create') }}" class="db-btn-primary">+ إضافة منطقة</a>
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
                    <th>المنطقة</th>
                    <th>تكلفة التنقل (دج)</th>
                    <th>الترتيب</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $z)
                <tr>
                    <td>{{ $z->id }}</td>
                    <td><strong>{{ $z->name }}</strong></td>
                    <td>{{ number_format($z->price) }} دج</td>
                    <td>{{ $z->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.travel-zones.edit', $z) }}" class="db-icon-btn db-edit-btn"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.travel-zones.destroy', $z) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="db-icon-btn db-delete-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4">لا توجد مناطق.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection