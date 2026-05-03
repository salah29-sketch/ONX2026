<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'ar' }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('panel.site_title') }}</title>

    <link href="{{ asset('img/logo2.png') }}" rel="icon">

    {{-- CSS Libraries --}}
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ONX Admin CSS (all styles centralised here) --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    @yield('styles')
</head>

<body id="onx-body" class="onx-light">

{{-- Mobile overlay --}}
<div id="sb-overlay" onclick="closeSidebar()"></div>

<div id="onx-shell">

    {{-- ══ SIDEBAR (يسار) ══ --}}
    <aside id="onx-sb">
        <div class="sb-logo">
            <span class="sb-logo-text">ONX</span>
            <span class="sb-logo-dot"></span>
        </div>

        <nav class="sb-nav">
            @include('partials.menu')
        </nav>

        <div class="sb-footer">
            <div class="sb-user">
                <div class="sb-user-avatar"><i class="fas fa-user-shield"></i></div>
                <div>
                    <span class="sb-user-name">{{ optional(auth()->user())->name ?? 'Admin' }}</span>
                    <span class="sb-user-email">{{ optional(auth()->user())->email ?? '' }}</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- ══ WRAP (يمين) ══ --}}
    <div id="onx-wrap">

        {{-- HEADER --}}
        <header id="onx-hd">
            {{-- Brand --}}
            <a href="{{ route('admin.home') }}" class="hd-brand">
                <span>{{ trans('panel.site_title') }}</span>
                <span class="hd-brand-dot"></span>
            </a>

            <div class="hd-spacer"></div>

            {{-- View site --}}
            <a href="{{ url('/') }}" target="_blank" class="hd-view-site">
                <i class="fas fa-external-link-alt"></i>
                {{ trans('global.menu.view_site') }}
            </a>

            {{-- Dark mode --}}
            <button class="hd-icon-btn" id="onx-theme-btn" title="تبديل الوضع">
                <i class="fas fa-moon" id="onx-icon-dark"></i>
                <i class="fas fa-sun"  id="onx-icon-light" style="display:none;"></i>
            </button>

            {{-- Language --}}
            @if(count(config('panel.available_languages', [])) > 1)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="hd-icon-btn" style="font-size:11px;font-weight:800;">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute left-0 top-full mt-1 min-w-[140px] rounded-xl border border-[var(--card-border)] bg-[var(--card-bg)] py-1 shadow-lg z-50"
                         style="display:none;">
                        @foreach(config('panel.available_languages') as $langLocale => $langName)
                            <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                               class="block px-4 py-2 text-[13px] font-semibold text-[var(--tx-secondary)] hover:bg-[var(--onx-orange-soft)] hover:text-[var(--onx-orange)] no-underline transition">
                                {{ strtoupper($langLocale) }} ({{ $langName }})
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Hamburger (mobile) --}}
            <button class="hd-icon-btn hd-hamburger" onclick="openSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </header>

        {{-- CONTENT BAR --}}
        <div id="onx-bar">
            @include('partials.admin_content_bar')
        </div>

        {{-- MAIN --}}
        <main id="onx-main">

            @if(session('message'))
                <div class="db-alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                </div>
            @endif

            @if(session('success'))
                <div class="db-alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="db-alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($errors->count() > 0)
                <div class="db-alert alert-danger mb-4">
                    <ul class="list-none mb-0">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

    </div>{{-- /onx-wrap --}}

</div>{{-- /onx-shell --}}

<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
    {{ csrf_field() }}
</form>

{{-- JS Libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>

<script>
$(function () {
    var langs = {
        en: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json',
        ar: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json'
    };
    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' });
    $.extend(true, $.fn.dataTable.defaults, {
        language: { url: langs['{{ app()->getLocale() }}'] || langs['en'] },
        columnDefs: [
            { orderable: false, className: 'select-checkbox', targets: 0 },
            { orderable: false, searchable: false, targets: -1 }
        ],
        select: { style: 'multi+shift', selector: 'td:first-child' },
        order: [], scrollX: true, pageLength: 25, lengthChange: false,
        dom: 'Bfrtip', buttons: []
    });
    $.fn.dataTable.ext.classes.sPageButton = '';
});
$(window).on('load', function () {
    if ($.fn.dataTable) $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
</script>

@include('partials.datatables_ajax_defaults')

<script>
// ── Dark mode ──
(function () {
    var body = document.getElementById('onx-body'),
        btn  = document.getElementById('onx-theme-btn'),
        iD   = document.getElementById('onx-icon-dark'),
        iL   = document.getElementById('onx-icon-light'),
        K    = 'onx_admin_theme';

    function isDark() { return body.classList.contains('onx-dark'); }

    function set(d) {
        body.classList.toggle('onx-dark',  d);
        body.classList.toggle('onx-light', !d);
        try { localStorage.setItem(K, d ? 'dark' : 'light'); } catch (e) {}
        if (iD) iD.style.display = d ? 'none' : 'inline-block';
        if (iL) iL.style.display = d ? 'inline-block' : 'none';
    }

    try { if (localStorage.getItem(K) === 'dark') set(true); } catch (e) {}
    if (btn) btn.addEventListener('click', function () { set(!isDark()); });
})();

// ── Mobile sidebar ──
function openSidebar() {
    document.getElementById('onx-sb').classList.add('open');
    document.getElementById('sb-overlay').classList.add('show');
}
function closeSidebar() {
    document.getElementById('onx-sb').classList.remove('open');
    document.getElementById('sb-overlay').classList.remove('show');
}
</script>

@stack('scripts')
@yield('scripts')

</body>
</html>