@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">إضافة منطقة تنقل</h1></div>
    <a href="{{ route('admin.travel-zones.index') }}" class="db-btn-secondary">عودة</a>
</div>
<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.travel-zones.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="db-label">اسم المنطقة *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name') }}" required placeholder="مثال: وهران المدينة">
            </div>
            <div class="mb-4">
                <label class="db-label">تكلفة التنقل (دج) *</label>
                <input type="number" name="price" class="db-input" value="{{ old('price', 0) }}" required min="0">
            </div>
            <div class="mb-4">
                <label class="db-label">الترتيب</label>
                <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', 0) }}">
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection