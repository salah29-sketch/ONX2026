@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">إضافة كود تخفيض</h1>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.promo-codes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>الكود *</label>
                <input type="text" name="code" class="db-input" value="{{ old('code') }}" required>
                @error('code')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>نوع التخفيض *</label>
                    <select name="discount_type" class="db-input" required>
                        <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>نسبة مئوية</option>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                    </select>
                </div>
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>القيمة *</label>
                    <input type="number" step="0.01" name="value" class="db-input" value="{{ old('value') }}" required>
                    @error('value')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label>الحد الأدنى لقيمة الطلب (اختياري)</label>
                <input type="number" step="0.01" name="min_order_value" class="db-input" value="{{ old('min_order_value') }}">
            </div>
            <div class="mb-4">
                <label>الحد الأقصى لعدد مرات الاستخدام (اختياري)</label>
                <input type="number" name="max_uses" class="db-input" value="{{ old('max_uses') }}">
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>صالح من</label>
                    <input type="date" name="valid_from" class="db-input" value="{{ old('valid_from') }}">
                </div>
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>صالح إلى</label>
                    <input type="date" name="valid_to" class="db-input" value="{{ old('valid_to') }}">
                </div>
            </div>
            <div class="mb-4">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> نشط</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
            <a href="{{ route('admin.promo-codes.index') }}" class="db-btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
