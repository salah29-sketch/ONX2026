@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">{{ __('panel.company_info') }}</h1>
        <div class="db-page-subtitle">معلومات الشركة والاتصال المعروضة في الموقع.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert-success db-alert">{{ session('success') }}</div>
@endif

<div class="db-card">
    <div class="db-card-header">
        <i class="fas fa-building me-2"></i>
        {{ __('panel.company_info') }}
    </div>

    <div class="db-card-body">
        <form action="{{ route('admin.company.update') }}" method="POST">
            @csrf
            @method('POST')

            <div class="mb-4">
                <label class="db-label">{{ __('panel.company_name') }}</label>
                <input type="text" name="company_name" class="db-input" value="{{ $setting->company_name ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.address') }}</label>
                <input type="text" name="address" class="db-input" value="{{ $setting->address ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.phone') }}</label>
                <input type="text" name="phone" class="db-input" value="{{ $setting->phone ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.email') }}</label>
                <input type="email" name="email" class="db-input" value="{{ $setting->email ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.facebook') }}</label>
                <input type="url" name="facebook" class="db-input" value="{{ $setting->facebook ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.instagram') }}</label>
                <input type="url" name="instagram" class="db-input" value="{{ $setting->instagram ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.twitter') }}</label>
                <input type="url" name="twitter" class="db-input" value="{{ $setting->twitter ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.linkedin') }}</label>
                <input type="url" name="linkedin" class="db-input" value="{{ $setting->linkedin ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="db-label">{{ __('panel.map_embed') }}</label>
                <textarea name="map_embed" class="db-input" rows="4">{{ $setting->map_embed ?? '' }}</textarea>
            </div>

            <div class="db-form-actions db-form-actions-lg">
                <button type="submit" class="db-btn-success">
                    <i class="fas fa-save"></i>
                    {{ __('panel.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
