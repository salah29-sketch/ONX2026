{{-- ═══════════════════════════════════════════════════════
     ONX EDGE — Admin Sidebar Menu
     resources/views/partials/menu.blade.php
     ملاحظة: جميع أنماط .onx-nav موجودة في resources/css/admin.css
═══════════════════════════════════════════════════════ --}}

<ul class="onx-nav">

    {{-- ══ الرئيسية ══ --}}
    <li>
        <a href="{{ route('admin.home') }}"
           class="onx-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <i class="fas fa-home onx-icon"></i>
            <span>{{ trans('global.dashboard') }}</span>
        </a>
    </li>

    {{-- ── فاصل ── --}}
    <li class="onx-sep-label">الحجوزات</li>

    {{-- ══ الحجوزات ══ --}}
    <li class="onx-group {{ request()->is('admin/bookings*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-calendar-check onx-icon"></i>
            <span>الحجوزات</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            <li>
                <a href="{{ route('admin.bookings.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/bookings*') && !request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
                    <i class="fas fa-list onx-icon"></i>
                    <span>قائمة الحجوزات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.bookings.calendar') }}"
                   class="onx-link onx-sub-link {{ request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt onx-icon"></i>
                    <span>تقويم المراقبة</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ── فاصل ── --}}
    <li class="onx-sep-label">الخدمات</li>

    {{-- ══ الخدمات والعروض ══ --}}
    <li class="onx-group {{ request()->is('admin/services*') || request()->is('admin/packages*') || request()->is('admin/categories*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-layer-group onx-icon"></i>
            <span>الخدمات والباقات</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            <li>
                <a href="{{ route('admin.categories.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fas fa-th-large onx-icon"></i>
                    <span>التصنيفات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.services.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/services*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell onx-icon"></i>
                    <span>الخدمات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/packages*') ? 'active' : '' }}">
                    <i class="fas fa-box-open onx-icon"></i>
                    <span>الباقات</span>
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{ route('admin.promo-codes.index') }}"
           class="onx-link {{ request()->is('admin/promo-codes*') ? 'active' : '' }}">
            <i class="fas fa-percentage onx-icon"></i>
            <span>أكواد التخفيض</span>
        </a>
    </li>

    {{-- ── فاصل ── --}}
    <li class="onx-sep-label">الفريق والعملاء</li>

    {{-- ══ العمال ══ --}}
    <li>
        <a href="{{ route('admin.workers.index') }}"
           class="onx-link {{ request()->is('admin/workers*') ? 'active' : '' }}">
            <i class="fas fa-id-badge onx-icon"></i>
            <span>العمال</span>
        </a>
    </li>

    {{-- ══ العملاء والرسائل ══ --}}
    <li class="onx-group {{ request()->is('admin/clients*') || request()->is('admin/client-messages*') || request()->is('admin/messages*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-users onx-icon"></i>
            <span>العملاء والرسائل</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            <li>
                <a href="{{ route('admin.clients.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/clients*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends onx-icon"></i>
                    <span>العملاء</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.messages.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/messages*') ? 'active' : '' }}">
                    <i class="fas fa-tags onx-icon"></i>
                    <span>رسائل العروض</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.client-messages.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/client-messages*') ? 'active' : '' }}">
                    <i class="fas fa-envelope onx-icon"></i>
                    <span>رسائل العملاء</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ── فاصل ── --}}
    <li class="onx-sep-label">المحتوى</li>

    {{-- ══ محتوى الموقع ══ --}}
    <li class="onx-group {{ request()->is('admin/portfolio-items*') || request()->is('admin/faqs*') || request()->is('admin/testimonials*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-photo-video onx-icon"></i>
            <span>محتوى الموقع</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            <li>
                <a href="{{ route('admin.portfolio-items.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/portfolio-items*') ? 'active' : '' }}">
                    <i class="fas fa-th-large onx-icon"></i>
                    <span>الأعمال (Portfolio)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.testimonials.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                    <i class="fas fa-star onx-icon"></i>
                    <span>آراء العملاء</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.faqs.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/faqs*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle onx-icon"></i>
                    <span>الأسئلة الشائعة</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ── فاصل ── --}}
    <li class="onx-sep-label">الإعدادات</li>

    {{-- ══ إعدادات النظام ══ --}}
    <li class="onx-group {{ request()->is('admin/eventlocations*') || request()->is('admin/company*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-sliders-h onx-icon"></i>
            <span>إعدادات النظام</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">

            <li>
                <a href="{{ route('admin.company') }}"
                   class="onx-link onx-sub-link {{ request()->routeIs('admin.company') ? 'active' : '' }}">
                    <i class="fas fa-building onx-icon"></i>
                    <span>معلومات الشركة</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ══ المستخدمون والصلاحيات ══ --}}
    <li class="onx-group {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'open' : '' }}">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-shield-alt onx-icon"></i>
            <span>المستخدمون والصلاحيات</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog onx-icon"></i>
                    <span>مستخدمو الإدارة</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase onx-icon"></i>
                    <span>الأدوار</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.permissions.index') }}"
                   class="onx-link onx-sub-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                    <i class="fas fa-unlock-alt onx-icon"></i>
                    <span>الصلاحيات</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ── فاصل نهائي ── --}}
    <li class="onx-sep"></li>

    {{-- اللغة --}}
    @if(count(config('panel.available_languages', [])) > 1)
    <li class="onx-group">
        <button class="onx-link onx-toggle" onclick="onxToggle(this)">
            <i class="fas fa-globe onx-icon"></i>
            <span>اللغة — {{ strtoupper(app()->getLocale()) }}</span>
            <i class="fas fa-chevron-down onx-arrow"></i>
        </button>
        <ul class="onx-sub">
            @foreach(config('panel.available_languages') as $langLocale => $langName)
            <li>
                <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                   class="onx-link onx-sub-link {{ app()->getLocale() === $langLocale ? 'active' : '' }}">
                    <i class="fas fa-check onx-icon" style="{{ app()->getLocale() === $langLocale ? 'opacity:1' : 'opacity:0' }}"></i>
                    <span>{{ $langName }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @endif

    {{-- Logout --}}
    <li>
        <a href="#" class="onx-link onx-logout"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fas fa-sign-out-alt onx-icon"></i>
            <span>تسجيل الخروج</span>
        </a>
    </li>

</ul>

{{-- Toggle JS (minimal — no inline styles needed) --}}
<script>
function onxToggle(btn) {
    var group  = btn.closest('.onx-group');
    var isOpen = group.classList.contains('open');
    document.querySelectorAll('#onx-sb .onx-group.open').forEach(function (g) {
        if (g !== group) g.classList.remove('open');
    });
    group.classList.toggle('open', !isOpen);
}
</script>