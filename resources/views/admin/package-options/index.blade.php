@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">خيارات الباقة: {{ $package->name }}</h1>
        <div class="db-page-subtitle">إدارة الخيارات الإضافية والمخصصة لهذه الباقة.</div>
    </div>
    <div>
        <a href="{{ route('admin.packages.index') }}" class="db-btn-secondary ms-2">عودة للباقات</a>
        <a class="db-btn-primary" href="{{ route('admin.packages.options.create', $package) }}">
            <i class="fas fa-plus"></i>
            إضافة خيار
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-danger">{{ session('error') }}</div>
@endif

<div class="db-card">
    <div class="db-card-body">
        <div class="overflow-x-auto">
            <table class="db-table db-table text-center">
                <thead>
                    <tr>
                        <th>التسمية</th>
                        <th>النوع</th>
                        <th>تأثير السعر</th>
                        <th>السعر</th>
                        <th>إلزامي</th>
                        <th>مفعل</th>
                        <th>الترتيب</th>
                        <th width="150">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($options as $opt)
                    <tr>
                        <td><strong>{{ $opt->label }}</strong></td>
                        <td>{{ strtoupper($opt->type) }}</td>
                        <td>{{ $opt->price_effect }}</td>
                        <td>{{ number_format($opt->price) }} د.ج</td>
                        <td>
                            {!! $opt->is_required ? '<span class="db-badge db-badge-cancelled">إلزامي</span>' : '<span class="db-badge db-badge-secondary">إختياري</span>' !!}
                        </td>
                        <td>
                            {!! $opt->is_active ? '<span class="db-badge db-badge-completed">نعم</span>' : '<span class="db-badge db-badge-secondary">لا</span>' !!}
                        </td>
                        <td>{{ $opt->sort_order }}</td>
                        <td>
                            {{-- تعديل --}}
                            <a class="text-sm db-btn-primary"
                               href="{{ route('admin.packages.options.edit', [$package, $opt]) }}"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- حذف --}}
                            <form action="{{ route('admin.packages.options.destroy', [$package, $opt]) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('حذف الخيار؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm db-btn-danger" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-muted text-center py-4">لا توجد خيارات مضافة لهذه الباقة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
