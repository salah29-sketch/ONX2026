{{-- Compare Modal (overlay) — receives: $maxItems --}}
<div x-show="showModal"
     x-cloak
     @keydown.escape.window="showModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-black/80 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div @click.outside="showModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-[#0d0d0d] border border-white/10 rounded-[24px] shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-white/8 shrink-0">
            <h3 class="text-base font-black text-white">⚖️ مقارنة الباقات</h3>
            <button @click="showModal = false" class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/50 hover:bg-white/10 hover:text-white text-sm transition">×</button>
        </div>

        {{-- Table --}}
        <div class="flex-1 overflow-x-auto overflow-y-auto p-4">
            <table class="w-full text-xs text-right border-collapse min-w-[400px]">
                <thead>
                    <tr>
                        <th class="px-3 py-2.5 text-right text-white/50 font-bold bg-white/[0.03] rounded-tr-xl w-[40%] sticky top-0">الخاصية</th>
                        <template x-for="p in compareList" :key="p.id">
                            <th class="px-3 py-2.5 text-center text-white bg-white/[0.03] font-black sticky top-0" x-text="p.name"></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-white/5">
                        <td class="px-3 py-2.5 font-bold text-white/60">💰 السعر</td>
                        <template x-for="p in compareList" :key="p.id">
                            <td class="px-3 py-2.5 text-center font-black text-orange-400"
                                x-text="p.price ? Number(p.price).toLocaleString('ar-DZ') + ' دج' : 'حسب الطلب'"></td>
                        </template>
                    </tr>
                    <template x-for="feat in allModalFeatures" :key="feat">
                        <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                            <td class="px-3 py-2.5 text-white/60" x-text="feat"></td>
                            <template x-for="p in compareList" :key="p.id">
                                <td class="px-3 py-2.5 text-center text-sm"
                                    x-text="p.features.includes(feat) ? '✅' : '❌'"></td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3.5 border-t border-white/8 flex flex-wrap gap-2 justify-end shrink-0">
            <template x-for="p in compareList" :key="p.id">
                <button type="button"
                        @click="openSbModal({id: p.id, name: p.name, price: p.price, priceText: p.price ? Number(p.price).toLocaleString('ar-DZ') + ' دج' : 'حسب الطلب', type: ''})"
                        class="bg-orange-500 hover:bg-orange-400 text-black px-5 py-2 rounded-full font-black text-xs transition">
                    احجز <span x-text="p.name"></span>
                </button>
            </template>
            <button @click="clear()"
                    class="border border-white/15 text-white/50 hover:text-red-400 hover:border-red-400/30 px-5 py-2 rounded-full font-bold text-xs transition">
                مسح الكل
            </button>
        </div>
    </div>
</div>
