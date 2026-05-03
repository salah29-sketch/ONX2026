@extends('layouts.app')
@section('content')
<div class="bg-white/[0.03] backdrop-blur-xl border border-white/10 rounded-[28px] p-8 shadow-[0_20px_60px_rgba(0,0,0,.5)]">
    {{-- Logo --}}
    <div class="text-center mb-6">
        <h1 class="text-3xl font-black tracking-tight">
            ONX<span class="text-orange-500">.</span>
        </h1>
    </div>

    {{-- Title --}}
    <h2 class="text-xl font-bold text-center mb-1">استعادة كلمة المرور</h2>
    <p class="text-white/40 text-sm text-center mb-8">أدخل بريدك الإلكتروني لإرسال رابط إعادة التعيين</p>

    <form method="POST" action="#">
        {{ csrf_field() }}

        {{-- Email --}}
        <div class="mb-6">
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input
                    type="email"
                    name="email"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    autofocus
                    placeholder="{{ trans('global.login_email') }}"
                >
            </div>
            @if($errors->has('email'))
                <p class="text-red-400 text-xs mt-2">{{ $errors->first('email') }}</p>
            @endif
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="bg-orange-500 hover:bg-orange-400 text-black font-black rounded-full py-3 w-full transition hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(249,115,22,.35)] cursor-pointer mb-6"
        >
            {{ trans('global.reset_password') }}
        </button>

        {{-- Back to login --}}
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-white/40 hover:text-orange-400 text-sm transition">
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                العودة لتسجيل الدخول
            </a>
        </div>
    </form>
</div>
@endsection
