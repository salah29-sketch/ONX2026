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
    <h2 class="text-xl font-bold text-center mb-1">تسجيل الدخول</h2>
    <p class="text-white/40 text-sm text-center mb-8">أدخل بياناتك للوصول إلى لوحة التحكم</p>

    {{-- Session message --}}
    @if(\Session::has('message'))
        <div class="bg-orange-500/10 border border-orange-500/20 text-orange-400 text-sm rounded-xl px-4 py-3 mb-6">
            {{ \Session::get('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        {{-- Email --}}
        <div class="mb-4">
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input
                    name="email"
                    type="text"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    autofocus
                    placeholder="{{ trans('global.login_email') }}"
                    value="{{ old('email', null) }}"
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
                    name="password"
                    type="password"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pr-11 pl-4 py-3 text-white placeholder:text-white/30 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                    required
                    placeholder="{{ trans('global.login_password') }}"
                >
            </div>
            @if($errors->has('password'))
                <p class="text-red-400 text-xs mt-2">{{ $errors->first('password') }}</p>
            @endif
        </div>

        {{-- Remember me --}}
        <div class="flex items-center mb-6">
            <input
                class="w-4 h-4 rounded border-white/20 bg-white/[0.04] text-orange-500 focus:ring-orange-500/20 focus:ring-offset-0"
                name="remember"
                type="checkbox"
                id="remember"
            >
            <label class="mr-2 text-sm text-white/50 cursor-pointer" for="remember">
                {{ trans('global.remember_me') }}
            </label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="bg-orange-500 hover:bg-orange-400 text-black font-black rounded-full py-3 w-full transition hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(249,115,22,.35)] cursor-pointer"
        >
            {{ trans('global.login') }}
        </button>
    </form>
</div>
@endsection
