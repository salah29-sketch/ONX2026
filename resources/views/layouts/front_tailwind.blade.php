<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'ONX — إنتاج بصري فاخر للإعلانات والحفلات والمشاريع. تصوير سينمائي، إعلانات تجارية، وتجارب بصرية تترك انطباعًا لا يُنسى.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <title>@yield('title', 'ONX')</title>

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:site_name" content="ONX">
    <meta property="og:title" content="@yield('og_title', 'ONX | إنتاج بصري فاخر')">
    <meta property="og:description" content="@yield('meta_description', 'إنتاج بصري فاخر للإعلانات والحفلات والمشاريع. تصوير سينمائي وإعلانات تجارية.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('img/events.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'ONX | إنتاج بصري فاخر')">
    <meta name="twitter:description" content="@yield('meta_description', 'إنتاج بصري فاخر للإعلانات والحفلات والمشاريع.')">
    <meta name="twitter:image" content="@yield('og_image', asset('img/events.jpg'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')

</head>
<body class="bg-[#050505] text-white antialiased selection:bg-orange-500/30 selection:text-white">
    {{-- الخلفية العامة --}}
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,106,0,0.16),transparent_32%),radial-gradient(circle_at_80%_20%,rgba(255,255,255,0.05),transparent_22%),linear-gradient(to_bottom,#070707,#050505,#020202)]"></div>
        <div class="absolute -top-24 right-[-120px] h-80 w-80 rounded-full bg-orange-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-120px] left-[-100px] h-96 w-96 rounded-full bg-orange-400/10 blur-3xl"></div>
    </div>

    {{-- NAVBAR --}}
    <header
        x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 50; window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 }, { passive: true })"
        id="main-header"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
        :class="scrolled ? 'bg-black/60 backdrop-blur-xl border-b border-white/10 shadow-[0_4px_30px_rgba(0,0,0,.4)]' : 'bg-transparent border-b border-transparent'"
    >
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-black tracking-[0.18em] text-white">ONX</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-orange-500 shadow-[0_0_20px_rgba(249,115,22,0.8)]"></span>
                </a>

                <nav class="hidden items-center gap-8 text-sm font-bold text-white/70 lg:flex">
                    <a href="/" class="transition duration-200 hover:text-white">الرئيسية</a>
                    <a href="/services" class="transition duration-200 hover:text-white">الخدمات</a>
                    <a href="/portfolio" class="transition duration-200 hover:text-white">الأعمال</a>
                    <a href="/booking" class="transition duration-200 hover:text-white">الحجز</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ Auth::guard('client')->check() ? route('client.dashboard') : route('client.login') }}"
                       class="rounded-full border border-white/20 bg-white/5 px-5 py-2.5 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white active:scale-[0.98]"
                       aria-label="{{ Auth::guard('client')->check() ? 'لوحة العملاء' : 'دخول منطقة العملاء' }}">
                        {{ Auth::guard('client')->check() ? 'حسابي' : 'منطقة العملاء' }}
                    </a>
                    <a href="/booking"
                       class="rounded-full border border-orange-500/40 bg-orange-500/10 px-5 py-2.5 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:border-orange-400 hover:bg-orange-500/20 hover:shadow-[0_0_24px_rgba(249,115,22,0.25)] active:scale-[0.98]">
                        ابدأ مشروعك
                    </a>
                </div>

                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white shadow-[0_0_20px_rgba(0,0,0,0.25)] transition hover:border-orange-500/50 hover:bg-orange-500/10 lg:hidden"
                    aria-label="فتح القائمة"
                >
                    <span x-show="!open" x-cloak class="text-2xl leading-none">☰</span>
                    <span x-show="open" x-cloak class="text-2xl leading-none">×</span>
                </button>
            </div>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                x-cloak
                class="pb-4 lg:hidden"
            >
                <div class="overflow-hidden rounded-[26px] border border-white/10 bg-white/5 p-3 shadow-[0_20px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl">
                    <nav class="flex flex-col">
                        <a href="/" class="rounded-2xl px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/5 hover:text-white">الرئيسية</a>
                        <a href="/services" class="rounded-2xl px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/5 hover:text-white">الخدمات</a>
                        <a href="/portfolio" class="rounded-2xl px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/5 hover:text-white">الأعمال</a>
                        <a href="/booking" class="rounded-2xl px-4 py-3 text-sm font-bold text-white/80 transition hover:bg-white/5 hover:text-white">الحجز</a>
                    </nav>

                    <div class="mt-3 flex flex-col gap-3 border-t border-white/10 pt-3">
                        <a href="{{ Auth::guard('client')->check() ? route('client.dashboard') : route('client.login') }}"
                           class="inline-flex w-full items-center justify-center rounded-full border border-white/20 bg-white/5 px-5 py-3 text-sm font-black text-white transition duration-300 active:scale-[0.98] hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white">
                            {{ Auth::guard('client')->check() ? 'حسابي' : 'منطقة العملاء' }}
                        </a>
                        <a href="/booking"
                           class="inline-flex w-full items-center justify-center rounded-full bg-orange-500 px-5 py-3 text-sm font-black text-black transition duration-300 active:scale-[0.98] hover:bg-orange-400">
                            ابدأ مشروعك
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="@hasSection('has_hero') @else pt-20 @endif">
        @if($errors->has('throttle'))
            <div class="sticky top-0 z-40 mx-auto max-w-7xl px-6 py-3 lg:px-8">
                <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-center text-sm font-bold text-amber-200">
                    {{ $errors->first('throttle') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="mt-24 border-t border-orange-500/20 bg-black/50 transition duration-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-14 lg:grid-cols-3 lg:px-8">
            <div>
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-2xl font-black tracking-[0.18em]">ONX</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>
                </div>
                <p class="max-w-md leading-8 text-white/65">
                    إنتاج بصري فاخر للإعلانات والحفلات والمشاريع التي تحتاج صورة تُرى وتُحَس، لا مجرد فيديو يمر مرور الكرام.
                </p>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-extrabold">روابط مهمة</h3>
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-white/65">
                    <ul class="space-y-3">
                        <li><a href="/" class="transition duration-200 hover:text-white">الرئيسية</a></li>
                        <li><a href="/services" class="transition duration-200 hover:text-white">الخدمات</a></li>
                        <li><a href="/portfolio" class="transition duration-200 hover:text-white">الأعمال</a></li>
                        <li><a href="/contact" class="transition duration-200 hover:text-white">تواصل معنا</a></li>
                    </ul>
                    <ul class="space-y-3">
                        <li><a href="/faq" class="transition duration-200 hover:text-white">الأسئلة الشائعة</a></li>
                        <li><a href="/booking" class="transition duration-200 hover:text-white">الحجز</a></li>
                        <li><a href="{{ route('booking.status') }}" class="transition duration-200 hover:text-white">تتبع حالة الحجز</a></li>
                        <li><a href="{{ route('client.login') }}" class="transition duration-200 hover:text-white">منطقة العملاء</a></li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-extrabold">تواصل معنا</h3>
                <div class="space-y-5">
                    <div>
                        <p class="mb-2 text-sm text-white/40">تواصل مباشر</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://wa.me/213540573518" target="_blank"
                               class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white/80 transition hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white">
                                واتساب
                            </a>
                            <a href="tel:+213540573518"
                               class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white/80 transition hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white">
                                اتصال
                            </a>
                            <a href="/booking"
                               class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white/80 transition hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white">
                                احجز الآن
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-sm text-white/40">follow us</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://instagram.com/onx.edge" target="_blank"
                               class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition duration-300 hover:scale-110 hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white hover:shadow-[0_0_20px_rgba(249,115,22,0.35)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm4.25 5a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.75-.9a1.1 1.1 0 100 2.2 1.1 1.1 0 000-2.2z"/>
                                </svg>
                            </a>
                            <a href="https://facebook.com/onx.edge" target="_blank"
                               class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition duration-300 hover:scale-110 hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white hover:shadow-[0_0_20px_rgba(249,115,22,0.35)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12a10 10 0 10-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.5-3.89 3.8-3.89 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.62.77-1.62 1.56V12h2.76l-.44 2.89h-2.32v6.99A10 10 0 0022 12z"/>
                                </svg>
                            </a>
                            <a href="https://tiktok.com/@onx.edge" target="_blank"
                               class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition duration-300 hover:scale-110 hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white hover:shadow-[0_0_20px_rgba(249,115,22,0.35)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16.5 3c.4 2.1 2 3.7 4.1 4.1v3a7.5 7.5 0 01-4.1-1.3v6.3a5.8 5.8 0 11-5.8-5.8c.3 0 .6 0 .9.1v3a2.8 2.8 0 102.1 2.7V3h2.8z"/>
                                </svg>
                            </a>
                            <a href="https://youtube.com/@onxedge" target="_blank"
                               class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/80 transition duration-300 hover:scale-110 hover:border-orange-500/50 hover:bg-orange-500/10 hover:text-white hover:shadow-[0_0_20px_rgba(249,115,22,0.35)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23 12s0-3.6-.46-5.19a2.8 2.8 0 00-1.97-1.97C19 4.4 12 4.4 12 4.4s-7 0-8.57.44A2.8 2.8 0 001.46 6.8C1 8.4 1 12 1 12s0 3.6.46 5.19a2.8 2.8 0 001.97 1.97C5 19.6 12 19.6 12 19.6s7 0 8.57-.44a2.8 2.8 0 001.97-1.97C23 15.6 23 12 23 12zM9.75 15.5v-7l6 3.5-6 3.5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-orange-500/20 py-5 text-center text-sm text-white/40">
            © ONX — onx-edge.com
        </div>
    </footer>

    {{-- Scroll reveal --}}
    <script>
      (function () {
        if (typeof window.IntersectionObserver === 'undefined') return;
        var els = document.querySelectorAll('[data-reveal]');
        if (!els.length) return;
        var observer = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
              }
            });
          },
          { rootMargin: '0px 0px -120px 0px', threshold: 0.12 }
        );
        els.forEach(function (el) { observer.observe(el); });
      })();
    </script>

    {{-- Navbar scroll effect --}}
    <script>
      (function () {
        var h = document.getElementById('main-header');
        if (!h) return;
        window.addEventListener('scroll', function () {
          if (window.scrollY > 50) {
            h.style.background = 'rgba(0,0,0,0.85)';
            h.style.backdropFilter = 'blur(20px)';
            h.style.webkitBackdropFilter = 'blur(20px)';
            h.style.borderBottomColor = 'rgba(255,255,255,0.1)';
          } else {
            h.style.background = 'transparent';
            h.style.backdropFilter = 'none';
            h.style.webkitBackdropFilter = 'none';
            h.style.borderBottomColor = 'transparent';
          }
        });
      })();
    </script>

    @stack('scripts')
</body>
</html>