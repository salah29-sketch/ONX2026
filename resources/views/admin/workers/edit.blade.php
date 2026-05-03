@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">تعديل عامل: {{ $worker->name }}</h1>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.workers.update', $worker) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label>الاسم *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name', $worker->name) }}" required>
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>البريد الإلكتروني *</label>
                <input type="email" name="email" class="db-input" value="{{ old('email', $worker->email) }}" required>
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>كلمة المرور (اتركها فارغة للإبقاء على الحالية)</label>
                <input type="password" name="password" class="db-input">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="db-input">
            </div>
            <div class="mb-4">
                <label>الهاتف</label>
                <input type="text" name="phone" class="db-input" value="{{ old('phone', $worker->phone) }}">
            </div>
            <div class="mb-4">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $worker->is_active) ? 'checked' : '' }}> نشط</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
            <a href="{{ route('admin.workers.index') }}" class="db-btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
