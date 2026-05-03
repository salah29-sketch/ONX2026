@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">رسائل العملاء</h1>
        <div class="db-page-subtitle">الرسائل المرسلة من منطقة العملاء.</div>
    </div>
</div>

@if(session('message'))
    <div class="alert-success">{{ session('message') }}</div>
@endif

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الموضوع / مقتطف</th>
                        <th>التاريخ</th>
                        <th>مقروءة</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $m)
                        <tr class="{{ $m->admin_read_at ? '' : 'table-warning' }}">
                            <td>{{ $m->id }}</td>
                            <td class="text-right">
                                @if($m->client)
                                    <a href="{{ route('admin.clients.show', $m->client->id) }}">{{ $m->client->name }}</a>
                                    <br><small>{{ $m->client->email ?? $m->client->phone }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-right">
                                @if($m->subject)<strong>{{ $m->subject }}</strong><br>@endif
                                {{ Str::limit($m->message, 60) }}
                            </td>
                            <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $m->admin_read_at ? 'نعم' : 'جديد' }}</td>
                            <td>
                                <a href="{{ route('admin.client-messages.show', $m) }}" class="db-btn-primary text-sm">عرض / رد</a>
                                @if(!$m->admin_read_at)
                                    <form action="{{ route('admin.client-messages.mark-read', ['message' => $m]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm db-btn-secondary">تحديد كمقروءة</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد رسائل.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $messages->links() }}</div>
    </div>
</div>
@endsection
