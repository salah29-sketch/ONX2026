@extends('layouts.front_tailwind')

@section('title', 'الأسئلة الشائعة - ONX')
@section('meta_description', 'الأسئلة الشائعة حول خدمات ONX والحجز: التوفر، الباقات، مدة التسليم، وطريقة التواصل.')

@section('content')

<section class="relative isolate overflow-hidden border-b border-white/10">
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('img/hero-bg1.jpg') }}"
             alt="الأسئلة الشائعة"
             class="h-full w-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/80 to-[#050505]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(255,106,0,0.14),transparent_28%)]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-bold text-white/70 opacity-0 backdrop-blur animate-fade-in-up">
                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                ONX • FAQ
            </div>
            <h1 class="text-3xl font-black text-white opacity-0 sm:text-4xl lg:text-5xl animate-fade-in-up animate-delay-100">الأسئلة الشائعة</h1>
            <p class="mt-4 text-sm leading-7 text-white/70 opacity-0 sm:text-base animate-fade-in-up animate-delay-200">
                إجابات سريعة تساعدك قبل الحجز. إذا لم تجد جوابًا، تواصل معنا مباشرة.
            </p>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
    <div class="mx-auto max-w-3xl space-y-3">
        @if($faqs->isNotEmpty())
            <div x-data="{ open: null }" class="space-y-3 opacity-0 animate-fade-in-up animate-delay-200">
                @foreach($faqs as $faq)
                    <div class="overflow-hidden rounded-[20px] border border-white/10 bg-white/5 backdrop-blur-xl transition duration-300 hover:border-white/20">
                        <button type="button"
                                @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}"
                                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-right">
                            <span class="text-sm font-black text-white">{{ $faq->question }}</span>
                            <span class="shrink-0 rounded-full border border-white/10 bg-black/30 px-3 py-1 text-xs font-bold text-white/70"
                                  x-text="open === {{ $faq->id }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="open === {{ $faq->id }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             x-cloak
                             class="px-5 pb-5 text-sm leading-7 text-white/70">
                            {{ $faq->answer }}
                            <div class="mt-3 text-xs text-white/45">
                                <a href="/contact" class="font-bold text-orange-400 hover:text-orange-300">تواصل معنا</a>
                                أو
                                <a href="/booking" class="font-bold text-orange-400 hover:text-orange-300">اذهب للحجز</a>.
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-[24px] border border-white/10 bg-white/5 p-8 text-center">
                <p class="text-white/70">لا توجد أسئلة معروضة حالياً.</p>
                <p class="mt-2 text-sm text-white/50">يمكنك <a href="/contact" class="font-bold text-orange-400">التواصل معنا</a> أو <a href="/booking" class="font-bold text-orange-400">الحجز مباشرة</a>.</p>
            </div>
        @endif
    </div>
</section>

@endsection
