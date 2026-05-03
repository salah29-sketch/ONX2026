@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <h1 class="db-page-title">إضافة خدمة</h1>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label>الاسم *</label>
                <input type="text" name="name" class="db-input" value="{{ old('name') }}" required>
                @error('name')<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>أيقونة (Emoji)</label>
                <input type="text" name="icon" class="db-input" value="{{ old('icon') }}" placeholder="مثال: 🎉 أو 📢" style="max-width:120px">
            </div>
            <div class="mb-4">
                <label>Slug (اختياري — يُولّد تلقائياً من الاسم)</label>
                <input type="text" name="slug" class="db-input" value="{{ old('slug') }}" placeholder="مثال: events">
                @error('slug')<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>التصنيف</label>
                <select name="category_id" class="db-input">
                    <option value="">— بدون تصنيف —</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label>الوصف</label>
                <textarea name="description" class="db-input" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label>صورة Hero (اختياري)</label>
                <input type="file" name="hero_image" class="db-input" accept="image/jpeg,image/png,image/webp">
                <small class="text-[var(--tx-muted)]">JPG, PNG أو WebP — حد أقصى 5MB</small>
                @error('hero_image')<span class="text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="db-input" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="mb-8 pb-6 border-b border-[var(--card-border)]">
                <h3 class="text-lg font-bold mb-4">إعدادات الحجز (Booking System)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>نوع الحجز *</label>
                        <select name="booking_type" class="db-input" required>
                            <option value="event" {{ old('booking_type') == 'event' ? 'selected' : '' }}>الفعاليات والأعراس (Event)</option>
                            <option value="appointment" {{ old('booking_type') == 'appointment' ? 'selected' : '' }}>المواعيد والاستشارات (Appointment)</option>
                            <option value="subscription" {{ old('booking_type') == 'subscription' ? 'selected' : '' }}>الاشتراكات الشهرية (Subscription)</option>
                        </select>
                        @error('booking_type')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label>العربون المطلوب (دج)</label>
                        <input type="number" name="deposit_amount" class="db-input" value="{{ old('deposit_amount', 10000) }}">
                        @error('deposit_amount')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label>طريقة حساب الوقت</label>
                        <select name="time_mode" class="db-input">
                            <option value="fixed" {{ old('time_mode') == 'fixed' ? 'selected' : '' }}>ثابت (بدون حساب)</option>
                            <option value="hourly" {{ old('time_mode') == 'hourly' ? 'selected' : '' }}>بالساعة (Hourly)</option>
                            <option value="wedding" {{ old('time_mode') == 'wedding' ? 'selected' : '' }}>حفلات (Wedding) - افتراضي من 19 إلى 04</option>
                        </select>
                    </div>
                    <div>
                        <label>وقت البداية الافتراضي</label>
                        <input type="time" name="default_start_time" class="db-input" value="{{ old('default_start_time') }}">
                    </div>
                    <div>
                        <label>وقت النهاية الافتراضي</label>
                        <input type="time" name="default_end_time" class="db-input" value="{{ old('default_end_time') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>غرامة التبكير (قبل الوقت) / دج</label>
                        <input type="number" name="early_start_price" class="db-input" value="{{ old('early_start_price', 0) }}">
                    </div>
                    <div>
                        <label>غرامة التأخير (بعد الوقت) / دج</label>
                        <input type="number" name="late_end_price" class="db-input" value="{{ old('late_end_price', 0) }}">
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_venue_selector" value="1" {{ old('show_venue_selector') ? 'checked' : '' }}>
                        إظهار حقل اختيار مكان الحفل (القاعة) في نموذج الحجز
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_wilaya_selector" value="1" {{ old('show_wilaya_selector') ? 'checked' : '' }}>
                        إظهار حقل اختيار الولاية (حساب التنقل) في نموذج الحجز
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> نشط</label>
            </div>
            <button type="submit" class="db-btn-primary">حفظ</button>
            <a href="{{ route('admin.services.index') }}" class="px-3 py-1.5 text-sm font-bold rounded-lg bg-[var(--body-bg)] border border-[var(--card-border)] text-[var(--tx-secondary)] hover:bg-[var(--onx-orange-soft)] transition">إلغاء</a>
        </form>
    </div>
</div>
@endsection
