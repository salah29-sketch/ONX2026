@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">الولايات</h1></div>
    <a href="{{ route('admin.wilayas.create') }}" class="db-btn-primary">+ إضافة ولاية</a>
</div>
@if(session('success'))
    <div class="db-alert db-alert-success">{{ session('success') }}</div>
@endif
<div class="db-card">
    <div class="db-card-body">
        <table class="db-table text-center">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>الاسم</th>
                    <th>منطقة التنقل</th>
                    <th>تكلفة التنقل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wilayas as $w)
                <tr>
                    <td>{{ $w->code }}</td>
                    <td>{{ $w->name }}</td>
                    <td>{{ $w->travelZone?->name ?? '—' }}</td>
                    <td>{{ $w->travelZone ? number_format($w->travelZone->price) . ' دج' : '—' }}</td>
                    <td>
                        <a href="{{ route('admin.wilayas.edit', $w) }}" class="db-icon-btn db-edit-btn"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.wilayas.destroy', $w) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="db-icon-btn db-delete-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4">لا توجد ولايات.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection