@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">أكواد التخفيض</h1>
        <div class="db-page-subtitle">إدارة أكواد البرومو للتخفيض على الحجوزات.</div>
    </div>
    <a class="db-btn-primary" href="{{ route('admin.promo-codes.create') }}">
        <i class="fas fa-plus"></i>
        إضافة كود
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
                        <th>الكود</th>
                        <th>النوع</th>
                        <th>القيمة</th>
                        <th>الاستخدام</th>
                        <th>نشط</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $pc)
                    <tr>
                        <td>{{ $pc->id }}</td>
                        <td><code>{{ $pc->code }}</code></td>
                        <td>{{ $pc->discount_type === 'percent' ? 'نسبة %' : 'مبلغ ثابت' }}</td>
                        <td>{{ $pc->discount_type === 'percent' ? $pc->value . '%' : number_format((float)$pc->value, 0) . ' DA' }}</td>
                        <td>{{ $pc->used_count }}{{ $pc->max_uses ? ' / ' . $pc->max_uses : '' }}</td>
                        <td>{{ $pc->is_active ? 'نعم' : 'لا' }}</td>
                        <td>
                            <a class="db-icon-btn db-edit-btn" href="{{ route('admin.promo-codes.edit', $pc) }}" title="تعديل"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.promo-codes.destroy', $pc) }}" method="POST" class="inline" onsubmit="return confirm('حذف الكود؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">لا توجد أكواد تخفيض.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $promoCodes->links() }}</div>
    </div>
</div>
@endsection
