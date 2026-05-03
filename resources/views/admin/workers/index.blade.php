@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">العمال</h1>
        <div class="db-page-subtitle">إدارة حسابات العمال (تسجيل الدخول من /worker/login).</div>
    </div>
    <a class="db-btn-primary" href="{{ route('admin.workers.create') }}">
        <i class="fas fa-plus"></i>
        إضافة عامل
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
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>الهاتف</th>
                        <th>نشط</th>
                        <th width="140"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workers as $w)
                    <tr>
                        <td>{{ $w->id }}</td>
                        <td><strong>{{ $w->name }}</strong></td>
                        <td>{{ $w->email }}</td>
                        <td>{{ $w->phone ?? '—' }}</td>
                        <td>{{ $w->is_active ? 'نعم' : 'لا' }}</td>
                        <td>
                            <a class="db-icon-btn db-edit-btn" href="{{ route('admin.workers.edit', $w) }}" title="تعديل"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.workers.destroy', $w) }}" method="POST" class="inline" onsubmit="return confirm('حذف العامل؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">لا يوجد عمال.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $workers->links() }}</div>
    </div>
</div>
@endsection
