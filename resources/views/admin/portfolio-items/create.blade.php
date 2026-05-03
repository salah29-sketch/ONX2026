@extends('layouts.admin')

@section('content')
<div class="portfolio-create-page">
    <div class="portfolio-page-head">
        <div>
            <div class="portfolio-page-badge">Portfolio / Admin</div>
            <h1 class="portfolio-page-title">إضافة عمل جديد</h1>
            <p class="portfolio-page-subtitle">
                أنشئ عنصرًا جديدًا داخل معرض الأعمال، واختر هل يعتمد على صورة مرفوعة أو رابط YouTube، وحدد أماكن ظهوره داخل الموقع.
            </p>
        </div>

        <div class="portfolio-page-actions">
            <a href="{{ route('admin.portfolio-items.index') }}" class="portfolio-btn-secondary">
                رجوع إلى الأعمال
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="portfolio-alert portfolio-alert-danger">
            <div class="portfolio-alert-title">يوجد خطأ في الإدخال</div>
            <ul class="portfolio-alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="portfolio-form-shell">
        <form method="POST" action="{{ route('admin.portfolio-items.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.portfolio-items.partials.form')

            <div class="portfolio-form-footer">
                <a href="{{ route('admin.portfolio-items.index') }}" class="portfolio-btn-light">
                    إلغاء
                </a>

                <button type="submit" class="portfolio-btn-primary">
                    حفظ العمل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
