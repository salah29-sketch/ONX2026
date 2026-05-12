@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">تعديل منطقة: {{ $travelZone->name }}</h1></div>
    <a href="{{ route('admin.travel-zones.index') }}" class="db-btn-secondary">عودة</a>
</div>
<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.travel-zones.update', $travelZone) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="db-label">اسم المنطقة *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name', $travelZone->name) }}" required>
            </div>
            <div class="mb-4">
                <label class="db-label">تكلفة التنقل (دج) *</label>
                <input type="number" name="price" class="db-input" value="{{ old('price', $travelZone->price) }}" required min="0">
            </div>
            <div class="mb-4">
                <label class="db-label">الترتيب</label>
                <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', $travelZone->sort_order) }}">
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection