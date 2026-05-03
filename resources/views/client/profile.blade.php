@extends('client.layout')

@section('title', 'الملف الشخصي - بوابة العملاء')

@push('styles')
<style>
.profile-header {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.profile-avatar {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    border: 2px solid #fcd34d;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; font-weight: 900; color: #b45309;
    flex-shrink: 0;
}
.profile-name  { font-size: 18px; font-weight: 900; color: #1f2937; }
.profile-email { font-size: 13px; color: #6b7280; margin-top: 2px; }

.profile-section {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 22px 24px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.profile-section h3 {
    font-size: 15px; font-weight: 900;
    color: #1f2937; margin-bottom: 4px;
}
.profile-section .sub {
    font-size: 12px; color: #6b7280; margin-bottom: 18px;
}

.form-field { margin-bottom: 14px; }
.form-label {
    display: block;
    font-size: 12px; font-weight: 700;
    color: #374151; margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .03em;
}
.form-input {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #f9fafb;
    padding: 11px 16px;
    font-family: inherit; font-size: 14px;
    color: #1f2937;
    transition: border-color .2s, background .2s;
}
.form-input:focus { outline: none; border-color: #fbbf24; background: #fff; }

.btn-save {
    background: #f59e0b; color: #fff;
    border: none; padding: 11px 28px;
    border-radius: 999px; font-weight: 900;
    font-family: inherit; font-size: 14px;
    cursor: pointer; transition: background .2s;
}
.btn-save:hover { background: #d97706; }

.btn-password {
    background: transparent;
    border: 1px solid #e5e7eb;
    color: #374151;
    padding: 11px 28px;
    border-radius: 999px; font-weight: 900;
    font-family: inherit; font-size: 14px;
    cursor: pointer; transition: all .2s;
}
.btn-password:hover { border-color: #fbbf24; color: #b45309; background: #fef3c7; }

/* Dark mode */
.client-portal-dark .profile-header   { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .profile-name     { color: #fff !important; }
.client-portal-dark .profile-email    { color: rgba(255,255,255,.5) !important; }
.client-portal-dark .profile-avatar   { background: linear-gradient(135deg,rgba(245,166,35,.25),rgba(249,115,22,.2)) !important; border-color: rgba(245,166,35,.35) !important; }
.client-portal-dark .profile-section  { background: #151b25 !important; border-color: rgba(255,255,255,.07) !important; }
.client-portal-dark .profile-section h3 { color: #fff !important; }
.client-portal-dark .form-label       { color: rgba(255,255,255,.6) !important; }
.client-portal-dark .form-input       { background: rgba(255,255,255,.05) !important; border-color: rgba(255,255,255,.1) !important; color: #fff !important; }
.client-portal-dark .form-input:focus { border-color: #f59e0b !important; background: rgba(255,255,255,.07) !important; }
.client-portal-dark .btn-password     { border-color: rgba(255,255,255,.1) !important; color: rgba(255,255,255,.7) !important; }
.client-portal-dark .btn-password:hover { border-color: #f59e0b !important; color: #fbbf24 !important; background: rgba(245,166,35,.1) !important; }
</style>
@endpush

@section('client_content')

{{-- Profile Header --}}
<div class="profile-header">
    <div class="profile-avatar">
        @if(!empty($client->is_company) && !empty($client->business_name))
            {{ mb_substr($client->business_name, 0, 1) }}
        @else
            {{ mb_substr($client->name ?? '؟', 0, 1) }}
        @endif
    </div>
    <div>
        @if(!empty($client->is_company) && !empty($client->business_name))
            <div class="profile-name">{{ $client->business_name }}</div>
            <div class="profile-email">{{ $client->name }} · {{ $client->email ?? $client->phone ?? '' }}</div>
            <div class="mt-1"><span class="badge-company"><i class="bi bi-building"></i> شركة</span></div>
        @else
            <div class="profile-name">{{ $client->name }}</div>
            <div class="profile-email">{{ $client->email ?? $client->phone ?? '' }}</div>
            <div class="mt-1"><span class="badge-individual"><i class="bi bi-person"></i> فرد</span></div>
        @endif
    </div>
</div>

{{-- تعديل المعلومات --}}
<div class="profile-section">
    <h3>المعلومات الشخصية</h3>
    <p class="sub">تحديث اسمك وبريدك ورقم هاتفك ونوع الحساب</p>
    <form method="POST" action="{{ route('client.profile.update') }}"
          x-data="{ isCompany: {{ !empty($client->is_company) ? 'true' : 'false' }} }">
        @csrf
        @method('PUT')
        <div class="form-field">
            <label class="form-label">الاسم الشخصي</label>
            <input type="text" name="name" value="{{ old('name', $client->name) }}" required class="form-input">
        </div>
        <div class="form-field">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="form-input">
        </div>
        <div class="form-field">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" required class="form-input">
        </div>

        {{-- نوع الحساب --}}
        <div class="form-field">
            <label class="form-label">نوع الحساب</label>
            <div class="flex gap-3 mt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="radio" name="is_company" value="0" :checked="!isCompany" @change="isCompany = false" class="accent-amber-500">
                    <span class="text-sm font-bold text-gray-700">فرد</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="radio" name="is_company" value="1" :checked="isCompany" @change="isCompany = true" class="accent-amber-500">
                    <span class="text-sm font-bold text-gray-700">شركة</span>
                </label>
            </div>
        </div>
        <div class="form-field" x-show="isCompany" x-transition>
            <label class="form-label">اسم الشركة</label>
            <input type="text" name="business_name" value="{{ old('business_name', $client->business_name) }}" class="form-input" placeholder="مثال: شركة النور للإعلانات">
        </div>

        <button type="submit" class="btn-save">حفظ التغييرات</button>
    </form>
</div>

{{-- تغيير كلمة المرور --}}
<div class="profile-section">
    <h3>تغيير كلمة المرور</h3>
    <p class="sub">يُنصح باستخدام كلمة مرور قوية لا تقل عن 8 أحرف</p>
    <form method="POST" action="{{ route('client.password.update') }}">
        @csrf
        @method('PUT')
        <div class="form-field">
            <label class="form-label">كلمة المرور الحالية</label>
            <input type="password" name="current_password" required class="form-input">
        </div>
        <div class="form-field">
            <label class="form-label">كلمة المرور الجديدة</label>
            <input type="password" name="password" required minlength="6" class="form-input">
        </div>
        <div class="form-field">
            <label class="form-label">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" required minlength="6" class="form-input">
        </div>
        <button type="submit" class="btn-password">تغيير كلمة المرور</button>
    </form>
</div>

@endsection
