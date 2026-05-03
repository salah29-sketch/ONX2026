@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">إضافة سؤال شائع</h1>
        <div class="db-page-subtitle">سؤال وجواب يظهر في صفحة FAQ.</div>
    </div>
    <a href="{{ route('admin.faqs.index') }}" class="db-btn-primary">رجوع</a>
</div>

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-plus me-2"></i>
        إضافة سؤال
    </div>
    <div class="db-card-body">
        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf

            <div class="mb-4">
                <label>السؤال <span class="text-danger">*</span></label>
                <input type="text" name="question" class="db-input" value="{{ old('question') }}" required maxlength="500" placeholder="مثال: كيف أعرف أن تاريخ الحفلة متاح؟">
                @error('question')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label>الإجابة <span class="text-danger">*</span></label>
                <textarea name="answer" class="db-input" rows="4" required maxlength="2000" placeholder="نص الإجابة...">{{ old('answer') }}</textarea>
                @error('answer')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="mb-4">
                        <label class="block">نشط</label>
                        <label class="mb-0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            عرض السؤال في الموقع
                        </label>
                    </div>
                </div>
            </div>

            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    حفظ السؤال
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="db-btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
