@extends('layouts.front_tailwind')

@section('title', 'تواصل معنا - ONX')
@section('meta_description', 'تواصل مع ONX — أرسل استفسارك أو طلبك عبر النموذج. إنتاج بصري فاخر للإعلانات والحفلات.')
@section('has_hero', true)

@section('content')

{{-- HERO --}}
<section class="relative isolate overflow-hidden border-b border-white/10">
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('img/hero-bg1.jpg') }}"
             alt="تواصل مع ONX"
             class="h-full w-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/80 to-[#050505]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(255,106,0,0.14),transparent_28%)]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-bold text-white/70 opacity-0 backdrop-blur animate-fade-in-up">
                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                ONX • Contact
            </div>
            <h1 class="text-3xl font-black text-white opacity-0 sm:text-4xl lg:text-5xl animate-fade-in-up animate-delay-100">
                تواصل معنا
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/70 opacity-0 sm:text-base animate-fade-in-up animate-delay-200">
                لديك سؤال أو فكرة مشروع؟ املأ النموذج وسنتواصل معك في أقرب وقت.
            </p>
        </div>
    </div>
</section>

{{-- FORM + SUCCESS --}}
<section class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
    @if(session('success'))
        <div class="mx-auto max-w-xl rounded-[24px] border border-orange-500/30 bg-orange-500/10 p-8 text-center opacity-0 animate-fade-in-up">
            <div class="mb-4 text-4xl">✓</div>
            <h2 class="text-xl font-black text-white">تم الإرسال بنجاح</h2>
            <p class="mt-3 text-sm text-white/80">{{ session('success') }}</p>
            <a href="/" class="mt-6 inline-flex rounded-full bg-orange-500 px-6 py-2.5 text-sm font-black text-black transition hover:bg-orange-400">
                العودة للرئيسية
            </a>
        </div>
    @else
        <div class="mx-auto max-w-xl opacity-0 animate-fade-in-up animate-delay-200">
            <form action="{{ route('contact.store') }}" method="post" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-white/80">الاسم <span class="text-orange-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 @error('name') border-red-500/50 @enderror"
                           placeholder="اسمك الكامل">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-white/80">البريد الإلكتروني <span class="text-orange-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 @error('email') border-red-500/50 @enderror"
                           placeholder="example@email.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-bold text-white/80">رقم الهاتف</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20"
                           placeholder="مثال: 0550123456">
                    @error('phone')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="mb-2 block text-sm font-bold text-white/80">الرسالة <span class="text-orange-500">*</span></label>
                    <textarea name="message" id="message" rows="5" required
                              class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 resize-none @error('message') border-red-500/50 @enderror"
                              placeholder="اكتب رسالتك أو تفاصيل طلبك...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full rounded-full bg-orange-500 py-3.5 text-base font-black text-black transition duration-300 hover:bg-orange-400 hover:shadow-[0_0_30px_rgba(249,115,22,0.3)]">
                    إرسال الرسالة
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-white/50">
                تفضل بالتواصل مباشرة عبر
                <a href="https://wa.me/213540573518" target="_blank" class="font-bold text-orange-400 hover:text-orange-300">واتساب</a>
                أو
                <a href="tel:+213540573518" class="font-bold text-orange-400 hover:text-orange-300">الاتصال</a>.
            </p>
        </div>
    @endif
</section>

@endsection
