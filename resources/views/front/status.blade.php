@extends('layouts.front_tailwind')

@section('title', 'حالة الخدمة | ONX')
@section('meta_description', 'تحقق من حالة أنظمة ONX والخدمات المتاحة. اطلع على آخر التحديثات حول جاهزية النظام وحالة الحجوزات.')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-20 lg:px-8">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-2xl backdrop-blur-xl">
        <h1 class="mb-2 text-3xl font-black tracking-tight text-white">حالة الخدمة</h1>
        <p class="mb-8 text-white/60">آخر تحديث: {{ now()->format('Y-m-d H:i') }}</p>
        <div class="inline-flex items-center gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-6 py-4">
            <span class="h-4 w-4 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.6)]"></span>
            <span class="text-lg font-bold text-emerald-300">جميع الأنظمة تعمل بشكل طبيعي</span>
        </div>
        <p class="mt-6 text-sm text-white/50">في حال مواجهة أي مشكلة، يرجى <a href="{{ route('contact') }}" class="text-orange-400 underline hover:text-orange-300">التواصل معنا</a>.</p>
    </div>
</div>
@endsection
