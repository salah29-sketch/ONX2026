{{--
Contact Form Partial
@param string $panelTitle (default 'معلومات الاتصال')
--}}
@php $panelTitle = $panelTitle ?? 'معلومات الاتصال'; @endphp

<div class="flex items-center gap-3 mb-6">
    <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
    <h2 class="text-lg font-bold text-white">{{ $panelTitle }}</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
    <div class="flex flex-col gap-2">
        <label class="text-xs text-white/60">الاسم الكامل <span class="text-orange-500">*</span></label>
        <input type="text"
            class="bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-colors w-full"
            placeholder="الاسم" wire:model="name">
        @error('name') <div class="text-[10px] text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label class="text-xs text-white/60">رقم الهاتف <span class="text-orange-500">*</span></label>
        <input type="tel"
            class="bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-colors w-full"
            placeholder="05-- -- -- --" dir="ltr" wire:model="phone">
        @error('phone') <div class="text-[10px] text-red-500 mt-1 text-right">{{ $message }}</div> @enderror
    </div>

    <div class="flex flex-col gap-2 md:col-span-2">
        <label class="text-xs text-white/60">البريد الإلكتروني <span class="text-orange-500">*</span></label>
        <input type="email"
            class="bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-colors w-full text-left"
            placeholder="hello@example.com" dir="ltr" wire:model="email">
        @error('email') <div class="text-[10px] text-red-500 mt-1 text-right">{{ $message }}</div> @enderror
    </div>
</div>

<div class="h-px w-full bg-white/5 my-6"></div>

<div class="flex items-center gap-3 mb-4">
    <div class="w-2 h-2 rounded-full bg-white/30"></div>
    <h2 class="text-sm font-bold text-white">كود التخفيض (اختياري)</h2>
</div>

<div class="flex flex-col gap-2 max-w-sm">
    <div class="flex gap-2">
        <input type="text"
            class="bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-colors flex-grow uppercase"
            placeholder="أدخل الكود" wire:model="promoCode">
        <button type="button"
            class="bg-white/[0.08] hover:bg-white/10 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors border border-white/15"
            wire:click="checkPromo">
            تطبيق
        </button>
    </div>

    @if($promoResult ?? false)
        <div
            class="text-xs p-2 mt-1 rounded-lg {{ $promoResult['valid'] ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
            {{ $promoResult['message'] ?? ($promoResult['valid'] ? '✓ تم تطبيق الخصم' : 'كود غير صالح') }}
        </div>
    @endif
</div>