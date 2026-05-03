{{-- شريط أعلى المحتوى: رجوع، تاريخ اليوم، وفتحة بحث اختيارية --}}
<div class="admin-content-bar">
    <div class="admin-content-bar-inner">
        <a href="{{ route('admin.home') }}" class="admin-content-bar-back">
            <i class="fas fa-arrow-left"></i>
            {{ trans('global.dashboard') }}
        </a>
        @hasSection('admin_content_bar_search')
            <div class="admin-content-bar-search">
                @yield('admin_content_bar_search')
            </div>
        @endif
        <div class="admin-content-bar-date">
            <i class="far fa-calendar-alt"></i>
            <span>{{ now()->translatedFormat('d/m/Y') }}</span>
        </div>
    </div>
</div>
