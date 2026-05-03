@extends('layouts.front_tailwind')
@section('title', 'باقاتنا')
@section('meta_description', 'استعرض باقات وعروض ONX للإنتاج البصري. باقات مصممة لتناسب احتياجاتك في التصوير، الإعلانات، وتغطية الفعاليات بأسعار تنافسية.')

@section('content')

<div class="min-h-screen" x-data="packagesApp()">

    {{-- Hero --}}
    <section class="relative isolate overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/80 to-[#050505]"></div>
        </div>
        <div class="mx-auto max-w-7xl px-6 py-20 text-center">
            <h1 class="text-4xl font-black text-white sm:text-5xl">اختر باقتك المثالية</h1>
            <p class="mx-auto mt-4 max-w-xl text-sm leading-8 text-white/60">
                باقات مصممة لتناسب احتياجاتك
            </p>

            {{-- فلاتر Categories --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                <button @click="filter = 'all'"
                        :class="filter === 'all'
                            ? 'bg-orange-500 text-black'
                            : 'border border-white/15 bg-white/5 text-white hover:border-orange-500/50'"
                        class="rounded-full px-5 py-2 text-xs font-black transition">
                    الكل
                </button>
                @foreach($categories as $cat)
                <button @click="filter = '{{ $cat->slug }}'"
                        :class="filter === '{{ $cat->slug }}'
                            ? 'bg-orange-500 text-black'
                            : 'border border-white/15 bg-white/5 text-white hover:border-orange-500/50'"
                        class="rounded-full px-5 py-2 text-xs font-black transition">
                    {{ $cat->icon }} {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Packages --}}
    <div class="mx-auto max-w-7xl px-6 py-16">

        @foreach($categories as $cat)

        {{-- Category Section --}}
        <div x-show="filter === 'all' || filter === '{{ $cat->slug }}'"
             class="mb-20">

            <h2 class="text-2xl font-black text-white mb-2 flex items-center gap-3">
                <span>{{ $cat->icon }}</span>
                {{ $cat->name }}
            </h2>
            <div class="h-px bg-white/10 mb-10"></div>

            @foreach($cat->services as $service)

            @php
                $standardPackages = $service->packages->filter(fn($p) => $p->price !== null || !$p->is_buildable)->where('is_active', true);
                $customPackages   = $service->packages->filter(fn($p) => $p->price === null && $p->is_buildable)->where('is_active', true);
            @endphp

            {{-- Service Title --}}
            <div class="mb-6">
                <p class="text-xs font-bold text-orange-400 tracking-widest uppercase mb-1">
                    {{ $service->name }}
                </p>
            </div>

            {{-- Standard Packages --}}
            @if($standardPackages->isNotEmpty())
            <div class="mb-12">
                @php
                    $allFeatures = $standardPackages->flatMap(fn($p) => $p->features ?? [])->unique()->values()->all();
                    $count       = $standardPackages->count();
                @endphp

                <div class="grid grid-cols-1 gap-6
                    {{ $count === 2 ? 'sm:grid-cols-2 max-w-3xl' : '' }}
                    {{ $count >= 3 ? 'sm:grid-cols-2 lg:grid-cols-3' : '' }}
                    {{ $count === 1 ? 'max-w-md' : '' }}">

                    @foreach($standardPackages as $pkg)
                    <article class="rounded-[24px] border flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1
                        {{ $pkg->is_featured
                            ? 'border-orange-500/40 bg-white/[0.04] shadow-[0_0_40px_rgba(249,115,22,0.12)]'
                            : 'border-white/10 bg-white/[0.02]' }}">

                        @if($pkg->is_featured)
                        <div class="w-full py-2 text-center text-[10px] font-black tracking-wider"
                             style="background:linear-gradient(135deg,#D4AF37,#F5D060);color:#1a1a1a">
                            ⭐ الأكثر طلباً
                        </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">

                            {{-- Name --}}
                            <h4 class="text-lg font-black text-white mb-1">{{ $pkg->name }}</h4>
                            @if($pkg->subtitle)
                            <p class="text-xs text-white/45 mb-4">{{ $pkg->subtitle }}</p>
                            @endif

                            {{-- Price --}}
                            <div class="mb-5">
                                @if($pkg->price !== null)
                                    @if($pkg->old_price)
                                    <div class="text-sm text-white/30 line-through mb-0.5">
                                        {{ number_format((float)$pkg->old_price) }} دج
                                    </div>
                                    @endif
                                    <span class="text-3xl font-black {{ $pkg->is_featured ? 'text-orange-400' : 'text-white' }}">
                                        {{ number_format((float)$pkg->price) }}
                                    </span>
                                    <span class="text-white/40 text-sm mr-1">دج</span>
                                    @if($service->booking_type === 'subscription')
                                    <span class="text-white/40 text-xs">/ شهر</span>
                                    @endif
                                @else
                                    <span class="text-xl font-bold text-orange-400">
                                        {{ $pkg->price_note ?? 'حسب الطلب' }}
                                    </span>
                                @endif
                            </div>

                            {{-- Features --}}
                            @if($allFeatures)
                            <ul class="space-y-2 mb-6 flex-1">
                                @foreach($allFeatures as $feature)
                                @php $has = in_array($feature, $pkg->features ?? []); @endphp
                                <li class="flex items-center gap-2.5 text-sm
                                    {{ $has ? 'text-white/80' : 'text-white/25' }}">
                                    @if($has)
                                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-orange-500/15 text-orange-400">
                                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    @else
                                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-white/5">
                                        <svg class="h-2.5 w-2.5 text-white/20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                    @endif
                                    {{ $feature }}
                                </li>
                                @endforeach
                            </ul>
                            @endif

                            {{-- CTA --}}
                            <a href="{{ route('book', ['category' => $cat->id, 'service' => $service->id, 'type' => $service->booking_type]) }}"
                                class="mt-auto block w-full text-center rounded-full py-3 text-sm font-black transition duration-300
                                    {{ $pkg->is_featured
                                        ? 'bg-orange-500 hover:bg-orange-400 text-black'
                                        : 'border border-white/15 bg-white/5 text-white hover:border-orange-500/50 hover:bg-orange-500/10' }}">
                                {{ $service->booking_type === 'subscription' ? 'ابدأ الاشتراك' : 'احجز الآن' }}
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Custom/Buildable Packages → CTA --}}
            @foreach($customPackages as $pkg)
            <div class="rounded-[24px] border border-white/10 bg-white/[0.02] p-8 text-center mb-8">
                <p class="text-xs font-bold text-purple-400 tracking-widest mb-3">حسب الطلب</p>
                <h3 class="text-xl font-black text-white mb-2">{{ $pkg->name }}</h3>
                @if($pkg->description)
                <p class="text-sm text-white/50 mb-6">{{ $pkg->description }}</p>
                @endif
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('book', ['category' => $cat->id, 'service' => $service->id, 'type' => $service->booking_type]) }}"
                    class="rounded-full bg-orange-500 hover:bg-orange-400 text-black px-7 py-3 text-sm font-black transition">
                        تخصيص وحجز
                    </a>
                    <a href="https://wa.me/213540573518?text={{ urlencode('سلام ONX، حاب أستفسر عن ' . $pkg->name) }}"
                       target="_blank"
                       class="rounded-full border border-white/15 bg-white/5 text-white px-7 py-3 text-sm font-black transition hover:border-orange-500/50 hover:bg-orange-500/10">
                        واتساب
                    </a>
                </div>
            </div>
            @endforeach

            @endforeach
        </div>
        @endforeach

    </div>

</div>

@endsection

@push('scripts')
@endpush

<script>
function packagesApp() {
    return { filter: 'all' };
}
</script>