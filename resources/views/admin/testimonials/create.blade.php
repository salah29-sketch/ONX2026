@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">إضافة رأي عميل</h1>
        <div class="db-page-subtitle">شهادة تظهر في قسم «آراء العملاء» بالصفحة الرئيسية.</div>
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="db-btn-primary">رجوع</a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-plus me-2"></i>
        إضافة رأي
    </div>
    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf

            <div class="mb-4">
                <label>اسم العميل / المُعلِن <span class="text-danger">*</span></label>
                <input type="text" name="client_name" class="db-input" value="{{ old('client_name') }}" required placeholder="مثال: عميل — إعلان تجاري">
                @error('client_name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label>الدور أو الوصف (اختياري)</label>
                        <input type="text" name="client_role" class="db-input" value="{{ old('client_role') }}" placeholder="مثال: عميل — إعلان تجاري">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label>عنوان فرعي (اختياري)</label>
                        <input type="text" name="subtitle" class="db-input" value="{{ old('subtitle') }}" placeholder="مثال: علامة تجارية">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label>نص الرأي <span class="text-danger">*</span></label>
                <textarea name="content" class="db-input" rows="4" required placeholder="اقتباس أو شهادة العميل...">{{ old('content') }}</textarea>
                @error('content')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>التقييم (1–5)</label>
                        <input type="number" name="rating" class="db-input" value="{{ old('rating', 5) }}" min="1" max="5">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>الحرف الأول (للعرض)</label>
                        <input type="text" name="initial" class="db-input" value="{{ old('initial') }}" maxlength="10" placeholder="اتركه فارغاً لاستخدام أول حرف من الاسم">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block">نشط</label>
                <label class="mb-0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    عرض الرأي في الصفحة الرئيسية
                </label>
            </div>

            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    حفظ الرأي
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="db-btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
