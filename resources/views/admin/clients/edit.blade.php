@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل العميل</h1>
        <div class="db-page-subtitle">تحديث بيانات العميل: {{ $client->name ?: '—' }}.</div>
    </div>

    <div class="db-page-head-actions">
        <a href="{{ route('admin.clients.show', $client) }}" class="db-btn-secondary">
            <i class="fas fa-eye"></i>
            عرض
        </a>
        <a href="{{ route('admin.clients.index') }}" class="db-btn-secondary">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert-danger db-alert">
        <strong>يرجى تصحيح الأخطاء التالية:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="db-card db-card-form">
    <div class="db-card-header">
        <i class="fas fa-user-edit me-2"></i>
        بيانات العميل
    </div>

    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="db-form-client">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-4">
                    <label class="db-label required" for="name">
                        <i class="fas fa-user text-muted me-1"></i>
                        الاسم
                    </label>
                    <input
                        class="db-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $client->name) }}"
                        placeholder="اسم العميل الكامل"
                        autofocus
                    >
                    @if($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="col-span-12 md:col-span-6 mb-4">
                    <label class="db-label" for="phone">
                        <i class="fas fa-phone text-muted me-1"></i>
                        الهاتف
                    </label>
                    <input
                        class="db-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                        type="text"
                        name="phone"
                        id="phone"
                        value="{{ old('phone', $client->phone) }}"
                        placeholder="مثال: 05xxxxxxxx"
                    >
                    @if($errors->has('phone'))
                        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                    @endif
                </div>

                <div class="col-span-12 mb-4">
                    <label class="db-label" for="email">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        البريد الإلكتروني
                    </label>
                    <input
                        class="db-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $client->email) }}"
                        placeholder="example@domain.com"
                    >
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>

            <div class="db-form-actions db-form-actions-lg">
                <button class="db-btn-success" type="submit">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
                <a href="{{ route('admin.clients.index') }}" class="db-btn-secondary">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection