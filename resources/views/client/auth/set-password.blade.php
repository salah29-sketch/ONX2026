@extends('layouts.front_tailwind')

@section('title', 'ضبط كلمة المرور - ONX')

@section('content')
<section class="mx-auto max-w-md px-6 py-16">
    <div class="rounded-[24px] border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <h1 class="text-xl font-black text-white">ضبط كلمة المرور</h1>
        <p class="mt-2 text-sm text-white/60">اختر كلمة مرور لمتابعة طلبك من منطقة العملاء.</p>

        <form method="POST" action="{{ route('client.set-password.post') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <div>
                <label class="mb-1 block text-sm font-bold text-white/80">كلمة المرور</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-bold text-white/80">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required minlength="6"
                       class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-white">
            </div>
            <button type="submit" class="w-full rounded-full bg-orange-500 py-3 font-black text-black hover:bg-orange-400">حفظ والدخول</button>
        </form>
    </div>
</section>
@endsection
