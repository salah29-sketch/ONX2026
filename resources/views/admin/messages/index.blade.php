@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">رسائل العروض</h1>
        <div class="db-page-subtitle">استفسارات وطلبات العملاء من العروض غير المدفوعة.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- فلترة --}}
<div class="db-card mb-3">
    <div class="db-card-body py-2">
        <form class="flex items-center gap-3" method="GET">
            <select name="status" class="db-input" style="max-width:180px;" onchange="this.form.submit()">
                <option value="">كل الحالات</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جديدة</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>مقروءة</option>
                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>تم الرد</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>مغلقة</option>
            </select>
        </form>
    </div>
</div>

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>العرض</th>
                        <th>الموضوع</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th width="140"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $m)
                    <tr class="{{ !$m->admin_read_at ? 'table-warning' : '' }}">
                        <td>{{ $m->id }}</td>
                        <td><strong>{{ $m->displayName() }}</strong></td>
                        <td>{{ $m->phone ?? '—' }}</td>
                        <td>{{ $m->offer_id ? 'عقود/عروض مخصصة #' . $m->offer_id : '—' }}</td>
                        <td>{{ Str::limit($m->subject ?? $m->message, 40) }}</td>
                        <td>
                            @if($m->status === 'new')
                                <span class="db-badge db-badge-cancelled">جديدة</span>
                            @elseif($m->status === 'read')
                                <span class="db-badge db-badge-new">مقروءة</span>
                            @elseif($m->status === 'replied')
                                <span class="db-badge db-badge-completed">تم الرد</span>
                            @else
                                <span class="db-badge db-badge-secondary">مغلقة</span>
                            @endif
                        </td>
                        <td>{{ $m->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a class="db-icon-btn db-edit-btn" href="{{ route('admin.messages.show', $m) }}" title="عرض"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.messages.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('حذف الرسالة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-muted text-center py-4">لا توجد رسائل.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $messages->links() }}</div>
    </div>
</div>
@endsection
