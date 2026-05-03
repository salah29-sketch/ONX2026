{{-- Package Card — receives: $card (array), $allFeatures (array) --}}
@php
    $visibleCount = 5;
    $hasMore = count($allFeatures) > $visibleCount;
@endphp

<article class="group relative rounded-[28px] border bg-white/[0.03] backdrop-blur flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1.5
    {{ $card['isFeatured']
        ? 'border-orange-500/40 shadow-[0_0_40px_rgba(249,115,22,0.12)]'
        : 'border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.25)]' }}
    hover:border-orange-500/30"
    @if($card['showCompare'])
    :class="{ '!border-blue-400/60 !shadow-[0_0_30px_rgba(59,130,246,0.15)]': isIn({{ $card['id'] }}) }"
    @endif>

    @if($card['isFeatured'])
        <div class="w-full py-2.5 text-center text-[11px] font-black tracking-wider"
             style="background:linear-gradient(135deg,#D4AF37,#F5D060);color:#1a1a1a">
            ⭐ {{ $card['featuredLabel'] }}
        </div>
    @endif

    <div class="flex flex-col flex-1 p-6">
        {{-- Badges --}}
        <div class="mb-4 flex flex-wrap items-center justify-center gap-2">
            @if(!$card['isFeatured'])
                <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-[10px] font-extrabold tracking-[0.18em] text-white/50">
                    {{ $card['subtitle'] ?: 'PACKAGE' }}
                </span>
            @endif
            @if($card['typeBadge'])
                @php $badgeColor = $card['typeBadge']['color'] ?? 'blue'; @endphp
                <span class="inline-flex items-center gap-1 rounded-full bg-{{ $badgeColor }}-500/12 px-3 py-1 text-[10px] font-bold text-{{ $badgeColor }}-300 border border-{{ $badgeColor }}-500/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-{{ $badgeColor }}-400"></span>
                    {{ $card['typeBadge']['label'] }}
                </span>
            @endif
        </div>

        @if($card['isFeatured'] && !$card['typeBadge'])
            <div class="mb-3"></div>
        @endif

        {{-- Name --}}
        <div class="text-center mb-2">
            <h3 class="text-xl font-black text-white sm:text-2xl">{{ $card['name'] }}</h3>
        </div>

        {{-- Price --}}
        <div class="mb-5 text-center">
            @if($card['oldPrice'] && $card['price'] && $card['oldPrice'] > $card['price'])
                <div class="mb-1 text-base font-bold text-white/30 line-through">
                    {{ number_format($card['oldPrice']) }} DA
                </div>
            @endif
            <div class="flex items-baseline justify-center gap-1.5">
                @if($card['price'] !== null)
                    <span class="text-4xl font-black {{ $card['isFeatured'] ? 'text-orange-400' : 'text-white' }}">
                        {{ $card['priceDisplay'] }}
                    </span>
                    <span class="text-white/40 text-sm font-bold">{{ $card['currency'] }}</span>
                @else
                    <span class="text-xl font-bold text-orange-400">{{ $card['priceDisplay'] }}</span>
                @endif
            </div>
            @if($card['price'] !== null && $card['priceNote'])
                <p class="text-orange-400/70 text-xs font-medium mt-1">{{ $card['priceNote'] }}</p>
            @endif
        </div>

        {{-- Description --}}
        @if($card['description'])
        <p class="text-center text-sm leading-7 text-white/55 mb-5">{{ $card['description'] }}</p>
        @endif

        <div class="h-px bg-white/8 mb-5"></div>

        {{-- Features with Alpine.js expand --}}
        <div class="flex-1 mb-6" x-data="{ expanded: false }">
            <ul class="space-y-2">
                @foreach($allFeatures as $fIdx => $feature)
                @php $has = in_array($feature, $card['features']); @endphp
                <li x-show="expanded || {{ $fIdx }} < {{ $visibleCount }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-center gap-3 text-sm py-1.5
                           {{ $has ? 'text-white/80' : 'text-white/25' }}">
                    @if($has)
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-500/15 text-orange-400">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        </span>
                    @else
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/5 text-white/20">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </span>
                    @endif
                    <span>{{ $feature }}</span>
                </li>
                @endforeach
            </ul>

            @if($hasMore)
                <button type="button"
                        @click="expanded = !expanded"
                        class="mt-3 flex items-center gap-1.5 text-sm font-bold text-orange-400 transition hover:text-orange-300">
                    <span x-text="expanded ? 'إخفاء' : 'عرض الكل ({{ count($allFeatures) }})'"></span>
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="space-y-2.5 pt-4 border-t border-white/8">
            <button type="button"
                onclick="openSbModal({{ \Illuminate\Support\e(json_encode($card['bookingJs'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }})"
                class="block w-full text-center py-3.5 rounded-full font-black text-sm cursor-pointer transition duration-300
                    {{ $card['isFeatured']
                        ? 'bg-orange-500 hover:bg-orange-400 text-black hover:shadow-[0_0_30px_rgba(249,115,22,0.3)]'
                        : 'border border-white/15 bg-white/5 text-white hover:border-orange-500/50 hover:bg-orange-500/10' }}">
                {{ $card['bookingLabel'] }}
            </button>

            @if($card['showCompare'])
            <button type="button"
                    @click="toggle({{ $card['id'] }}, '{{ addslashes($card['name']) }}', {{ $card['price'] ?? 'null' }}, {{ $card['isFeatured'] ? 'true' : 'false' }}, {{ json_encode($card['features']) }})"
                    class="block w-full text-center py-2.5 rounded-full font-bold text-xs border transition duration-200"
                    :class="isIn({{ $card['id'] }})
                        ? 'border-blue-400/40 bg-blue-500/10 text-blue-300'
                        : 'border-white/8 text-white/40 hover:border-white/20 hover:text-white/60'">
                <span x-text="isIn({{ $card['id'] }}) ? '✓ في المقارنة' : '+ قارن'"></span>
            </button>
            @endif
        </div>
    </div>
</article>
