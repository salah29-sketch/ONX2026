@extends('layouts.front_tailwind')

@section('title', 'حجز المواعيد | ONX Edge')
@section('meta_description', 'احجز خدمتك الآن — فعاليات، مواعيد، أو اشتراكات. ONX Edge للإنتاج البصري الفاخر.')

@section('content')
<!-- Decorative Background Gradients -->
<div class="fixed top-0 left-1/4 w-[600px] h-[600px] bg-orange-500/5 rounded-full blur-[120px] pointer-events-none -z-10"></div>
<div class="fixed bottom-0 right-1/4 w-[400px] h-[400px] bg-sky-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

<!-- HEADER -->
<header class="pt-12 pb-8 md:pt-16 md:pb-8 text-center px-4 animate-fade-in-up">
    <h1 class="font-syne font-bold text-3xl md:text-4xl lg:text-5xl tracking-normal mb-4 text-white drop-shadow-md">
        احجز <span class="text-transparent bg-clip-text bg-gradient-to-l from-orange-400 to-orange-600">مشروعك</span> معنا
    </h1>
    <p class="text-neutral-400 text-sm md:text-base max-w-md mx-auto leading-relaxed">
        اختر الخدمة والموعد المناسب — نتواصل معك لتأكيد التفاصيل وتنفيذ أفكارك بأعلى جودة.
    </p>

    {{-- Toggle: Smart Form / Wizard --}}
    <div class="mt-6 inline-flex items-center bg-white/[0.04] border border-white/10 rounded-full p-1 gap-1">
        <a href="{{ request()->fullUrlWithQuery(['mode' => 'smart']) }}"
           class="px-4 py-1.5 rounded-full text-xs font-bold transition-all
               {{ request('mode', 'smart') !== 'wizard' ? 'bg-orange-500 text-white shadow-[0_0_12px_rgba(249,115,22,0.4)]' : 'text-white/50 hover:text-white' }}">
            ⚡ نموذج ذكي
        </a>
        <a href="{{ request()->fullUrlWithQuery(['mode' => 'wizard']) }}"
           class="px-4 py-1.5 rounded-full text-xs font-bold transition-all
               {{ request('mode') === 'wizard' ? 'bg-white/10 text-white' : 'text-white/50 hover:text-white' }}">
            خطوة بخطوة
        </a>
    </div>
</header>

<!-- MAIN BOOKING AREA -->
<main class="flex-grow pb-24 px-4 w-full max-w-4xl mx-auto">
    @if(request('mode') === 'wizard')
        @livewire('booking.booking-page')
    @else
        @livewire('booking.smart-booking-form')
    @endif
</main>
@endsection