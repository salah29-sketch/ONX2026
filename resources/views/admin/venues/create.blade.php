@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">إضافة قاعة</h1></div>
    <a href="{{ route('admin.venues.index') }}" class="db-btn-secondary">عودة</a>
</div>
<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.venues.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="db-label">اسم القاعة *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name') }}" required>
            </div>
            <div class="mb-4">
                <label class="db-label">الولاية</label>
                <select name="wilaya_id" class="db-input">
                    <option value="">-- اختر الولاية --</option>
                    @foreach($wilayas as $w)
                        <option value="{{ $w->id }}" {{ old('wilaya_id') == $w->id ? 'selected' : '' }}>{{ $w->code }} — {{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="db-label">العنوان</label>
                <input type="text" name="address" class="db-input" value="{{ old('address') }}">
            </div>
            <div class="mb-4">
                <label class="db-label">تكلفة التنقل (دج) — اتركه فارغاً لاستخدام تكلفة الولاية</label>
                <input type="number" name="travel_cost_override" class="db-input" value="{{ old('travel_cost_override') }}">
            </div>
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="db-label mb-0">نشطة</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection