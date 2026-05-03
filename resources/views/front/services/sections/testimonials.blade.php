<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="mb-12 text-center">
        <p class="mb-3 text-xs font-extrabold uppercase tracking-[0.25em] text-orange-400">آراء عملائنا</p>
        <h2 class="text-3xl font-black sm:text-4xl">قالوا عنّا</h2>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($testimonials as $t)
        <div class="rounded-[24px] border border-white/10 bg-white/[0.03] p-6 flex flex-col">
            <div class="flex gap-1 mb-4">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-amber-400' : 'text-white/15' }}"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
            </div>

            <p class="text-sm leading-8 text-white/65 flex-1 mb-5">"{{ $t->content }}"</p>

            <div class="flex items-center gap-3 pt-4 border-t border-white/8">
                <div class="w-9 h-9 rounded-full bg-orange-500/15 border border-orange-500/25 flex items-center justify-center text-sm font-black text-orange-400">
                    {{ mb_substr($t->client_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-black text-white">{{ $t->client_name }}</p>
                    @if($t->client_role)
                    <p class="text-xs text-white/40">{{ $t->client_role }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
