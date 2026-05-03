@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">آراء العملاء</h1>
        <div class="db-page-subtitle">إدارة الشهادات المعروضة في الصفحة الرئيسية.</div>
    </div>

    <a href="{{ route('admin.testimonials.create') }}" class="db-btn-primary">
        <i class="fas fa-plus"></i>
        إضافة رأي
    </a>
</div>

@if(session('message'))
    <div class="alert-success">{{ session('message') }}</div>
@endif

<div class="db-card mb-3">
    <div class="db-card-body">
        <form method="get" class="flex items-center gap-2">
            <label class="me-2">الحالة:</label>
            <select name="status" class="me-2" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>مصادق عليه</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </form>
    </div>
</div>

<div class="db-card">
    <div class="db-card-header">قائمة آراء العملاء (المصادق عليها تظهر في الموقع بشكل عشوائي)</div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل / الدور</th>
                        <th>مقتطف الرأي</th>
                        <th>التقييم</th>
                        <th>الحالة</th>
                        <th>نشط</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td class="text-right">
                                <strong>{{ $t->client_name }}</strong>
                                @if($t->client_role)<br><small class="text-muted">{{ $t->client_role }}</small>@endif
                                @if($t->client_id)<br><small class="text-info">من منطقة العميل</small>@endif
                            </td>
                            <td class="text-right" style="max-width:260px;">{{ Str::limit($t->content, 45) }}</td>
                            <td>{{ $t->rating }} ★</td>
                            <td>
                                @if($t->status === 'pending')
                                    <span class="db-badge db-badge-new">قيد المراجعة</span>
                                @elseif($t->status === 'approved')
                                    <span class="db-badge db-badge-completed">مصادق</span>
                                @else
                                    <span class="db-badge db-badge-secondary">مرفوض</span>
                                @endif
                            </td>
                            <td>{{ $t->is_active ? 'نعم' : 'لا' }}</td>
                            <td>
                                @if($t->status === 'pending')
                                    <form action="{{ route('admin.testimonials.approve', $t) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="db-btn-success text-sm" title="تصديق">✓ تصديق</button>
                                    </form>
                                    <form action="{{ route('admin.testimonials.reject', $t) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="db-btn-secondary text-sm" title="رفض">✗ رفض</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.testimonials.edit', $t) }}" class="db-icon-btn db-edit-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="db-empty">
                                    <i class="fas fa-comments"></i>
                                    لا توجد آراء. العملاء يضيفونها من منطقة العملاء أو أضف رأياً يدوياً من الزر أعلاه.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $testimonials->links() }}</div>
    </div>
</div>
@endsection
