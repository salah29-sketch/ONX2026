@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل رأي عميل</h1>
        <div class="db-page-subtitle">تعديل نص الرأي والبيانات.</div>
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="db-btn-primary">رجوع</a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-edit me-2"></i>
        تعديل الرأي
    </div>
    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>اسم العميل / المُعلِن <span class="text-danger">*</span></label>
                <input type="text" name="client_name" class="db-input" value="{{ old('client_name', $testimonial->client_name) }}" required>
                @error('client_name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label>الدور أو الوصف</label>
                        <input type="text" name="client_role" class="db-input" value="{{ old('client_role', $testimonial->client_role) }}">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label>عنوان فرعي</label>
                        <input type="text" name="subtitle" class="db-input" value="{{ old('subtitle', $testimonial->subtitle) }}">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label>نص الرأي <span class="text-danger">*</span></label>
                <textarea name="content" class="db-input" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
                @error('content')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>التقييم (1–5)</label>
                        <input type="number" name="rating" class="db-input" value="{{ old('rating', $testimonial->rating) }}" min="1" max="5">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>الحرف الأول</label>
                        <input type="text" name="initial" class="db-input" value="{{ old('initial', $testimonial->initial) }}" maxlength="10">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="mb-4">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', $testimonial->sort_order) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block">نشط</label>
                <label class="mb-0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                    عرض الرأي في الصفحة الرئيسية
                </label>
            </div>

            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="db-btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
