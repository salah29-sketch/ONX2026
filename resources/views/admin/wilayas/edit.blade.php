@extends('layouts.admin')
@section('content')
<div class="db-page-head">
    <div><h1 class="db-page-title">تعديل ولاية: {{ $wilaya->name }}</h1></div>
    <a href="{{ route('admin.wilayas.index') }}" class="db-btn-secondary">عودة</a>
</div>
<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.wilayas.update', $wilaya) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="db-label">الكود *</label>
                <input type="text" name="code" class="db-input" value="{{ old('code', $wilaya->code) }}" required>
            </div>
            <div class="mb-4">
                <label class="db-label">الاسم *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name', $wilaya->name) }}" required>
            </div>
            <div class="mb-4">
                <label class="db-label">منطقة التنقل</label>
                <select name="travel_zone_id" class="db-input">
                    <option value="">-- بدون منطقة --</option>
                    @foreach($zones as $z)
                        <option value="{{ $z->id }}" {{ old('travel_zone_id', $wilaya->travel_zone_id) == $z->id ? 'selected' : '' }}>
                            {{ $z->name }} — {{ number_format($z->price) }} دج
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection