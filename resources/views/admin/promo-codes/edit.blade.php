@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">تعديل كود: {{ $promoCode->code }}</h1>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.promo-codes.update', $promoCode) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label>الكود *</label>
                <input type="text" name="code" class="db-input" value="{{ old('code', $promoCode->code) }}" required>
                @error('code')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>نوع التخفيض *</label>
                    <select name="discount_type" class="db-input" required>
                        <option value="percent" {{ old('discount_type', $promoCode->discount_type) === 'percent' ? 'selected' : '' }}>نسبة مئوية</option>
                        <option value="fixed" {{ old('discount_type', $promoCode->discount_type) === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                    </select>
                </div>
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>القيمة *</label>
                    <input type="number" step="0.01" name="value" class="db-input" value="{{ old('value', $promoCode->value) }}" required>
                </div>
            </div>
            <div class="mb-4">
                <label>الحد الأدنى ل value الطلب</label>
                <input type="number" step="0.01" name="min_order_value" class="db-input" value="{{ old('min_order_value', $promoCode->min_order_value) }}">
            </div>
            <div class="mb-4">
                <label>الحد الأقصى لعدد مرات الاستخدام</label>
                <input type="number" name="max_uses" class="db-input" value="{{ old('max_uses', $promoCode->max_uses) }}">
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>صالح من</label>
                    <input type="date" name="valid_from" class="db-input" value="{{ old('valid_from', $promoCode->valid_from?->format('Y-m-d')) }}">
                </div>
                <div class="mb-4 col-span-12 md:col-span-6">
                    <label>صالح إلى</label>
                    <input type="date" name="valid_to" class="db-input" value="{{ old('valid_to', $promoCode->valid_to?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="mb-4">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoCode->is_active) ? 'checked' : '' }}> نشط</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
            <a href="{{ route('admin.promo-codes.index') }}" class="db-btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
