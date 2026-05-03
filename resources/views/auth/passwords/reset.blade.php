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
    <h2 class="text-xl font-bold text-center mb-1">تعيين كلمة مرور جديدة</h2>
    <p class="text-white/40 text-sm text-center mb-8">أدخل كلمة المرور الجديدة لحسابك</p>

    <form method="POST" action="#">
        {{ csrf_field() }}
        <input name="token" value="{{ $token }}" type="hidden">

        {{-- Email --}}
        <div class="mb-4">
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input
                    type="email"
                    name="email"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    placeholder="{{ trans('global.login_email') }}"
                >
            </div>
            @if($errors->has('email'))
                <p class="text-red-400 text-xs mt-2">{{ $errors->first('email') }}</p>
            @endif
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input
                    type="password"
                    name="password"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    placeholder="{{ trans('global.login_password') }}"
                >
            </div>
            @if($errors->has('password'))
                <p class="text-red-400 text-xs mt-2">{{ $errors->first('password') }}</p>
            @endif
        </div>

        {{-- Password Confirmation --}}
        <div class="mb-6">
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    placeholder="{{ trans('global.login_password_confirmation') }}"
                >
            </div>
            @if($errors->has('password_confirmation'))
                <p class="text-red-400 text-xs mt-2">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="bg-orange-500 hover:bg-orange-400 text-black font-black rounded-full py-3 w-full transition hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(249,115,22,.35)] cursor-pointer"
        >
            {{ trans('global.reset_password') }}
        </button>
    </form>
</div>
@endsection
