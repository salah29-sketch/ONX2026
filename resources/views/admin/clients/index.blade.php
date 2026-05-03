@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">العملاء</h1>
        <div class="db-page-subtitle">إدارة العملاء ومراجعة بياناتهم وحجوزاتهم المرتبطة.</div>
    </div>

    <a class="db-btn-primary" href="{{ route('admin.clients.create') }}">
        <i class="fas fa-plus"></i>
        إضافة عميل
    </a>
</div>

@if(session('message'))
    <div class="alert-success db-alert">{{ session('message') }}</div>
@endif

<div class="db-card db-card-clients">
    <div class="db-card-header">
        <i class="fas fa-users me-2"></i>
        قائمة العملاء
    </div>

    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table ajaxTable datatable datatable-Client db-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>كلمة السر</th>
                        <th>الدخول</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        var dtOverrideGlobals = Object.assign({}, window.dtArabicAjaxDefaults || {}, {
            ajax: "{{ route('admin.clients.index') }}",
            columns: [
                { data: 'id', name: 'id', width: '60px' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'has_password', name: 'has_password', orderable: false, searchable: false },
                { data: 'login_disabled', name: 'login_disabled', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, width: '180px' }
            ]
        });
        $('.datatable-Client').DataTable(dtOverrideGlobals);
    });
</script>
@endsection