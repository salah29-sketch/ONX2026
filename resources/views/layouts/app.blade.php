<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'ar' }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('panel.site_title') }}</title>
    <link href="{{ asset('img/logo2.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#050505] min-h-screen flex items-center justify-center font-[Cairo] text-white antialiased overflow-hidden relative">
    {{-- Background effects --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-orange-500/[0.07] rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-orange-600/[0.05] rounded-full blur-[100px]"></div>
    </div>
    <div class="relative z-10 w-full max-w-md px-6">
        @yield('content')
    </div>
</body>
</html>
