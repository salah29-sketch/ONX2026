{{--
    smart-booking-form.blade.php  — v2 (Fixed)
    إصلاحات:
    1. accordion state مدار من PHP عبر $openSections — لا يُعاد ضبطه عند كل re-render
    2. Script في @push('scripts') بدل inline
    3. CSS حقيقي بدل @apply
--}}

@php
    $pkgName = $selectedPackageId
        ? ($svc?->packages->firstWhere('id', $selectedPackageId)?->name)
        : ($isCustomPackage ? 'مخصصة' : null);
    $isEvent = $type === 'event';
    $isAppt  = $type === 'appointment';
    $isSub   = $type === 'subscription';

    $catOpen  = $openSections['category'] ?? true;
    $svcOpen  = $openSections['service']  ?? true;
    $pkgOpen  = $openSections['package']  ?? true;
    $dateOpen = $openSections['date']     ?? true;
    $locOpen  = $openSections['location'] ?? true;
    $conOpen  = $openSections['contact']  ?? true;
@endphp

{{-- ══ CONFIRMATION ══════════════════════════════════════════════════════════ --}}
@if($bookingComplete)
<div class="max-w-lg mx-auto">
    <div class="bg-white/[0.03] backdrop-blur-md border border-white/10 rounded-2xl p-8 md:p-12 text-center shadow-2xl">
        <div class="w-20 h-20 mx-auto bg-green-500/10 border-2 border-green-500 text-green-500 rounded-full flex items-center justify-center text-3xl mb-6">✓</div>
        <div class="font-syne font-bold text-2xl lg:text-3xl mb-2 text-white">تم استلام حجزك!</div>
        <div class="inline-block bg-white/[0.04] border border-orange-500/30 text-orange-400 font-syne font-bold text-lg px-6 py-2 rounded-full mb-6 tracking-wider">#{{ str_pad($bookingId, 4, '0', STR_PAD_LEFT) }}</div>
        <p class="text-white/60 text-sm mb-8 leading-relaxed">سنتواصل معك قريباً لتأكيد التفاصيل.</p>
        <div class="bg-orange-950/30 border border-orange-500/30 rounded-xl p-4 mb-8 text-right">
            <div class="text-sm font-bold text-orange-400 mb-1">⚠️ حجزك غير مؤكد بعد</div>
            <div class="text-xs text-orange-300/80 leading-relaxed">لتأكيد الحجز، يرجى دفع العربون خلال 24 ساعة.</div>
        </div>
        @if($generatedPassword)
        <div class="bg-white/[0.04] border border-white/5 rounded-xl p-5 mb-8 text-right">
            <div class="text-xs text-white/40 mb-4 font-semibold">بيانات دخولك — احتفظ بها الآن</div>
            <div class="flex justify-between items-center py-2 border-b border-white/5">
                <span class="text-xs text-white/60">البريد</span>
                <span class="font-bold text-sm text-white">{{ $email }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-xs text-white/60">كلمة المرور</span>
                <span class="font-bold text-sm text-orange-400 tracking-wider">{{ $generatedPassword }}</span>
            </div>
            <div class="text-[10px] text-red-400/80 mt-3 text-center">تظهر مرة واحدة فقط</div>
        </div>
        @endif
        <div class="flex flex-col gap-3">
            @auth
                <a href="{{ route('client.dashboard') }}" class="w-full bg-orange-500 hover:bg-orange-400 text-white text-sm font-bold py-3 px-6 rounded-xl text-center">ادخل للوحتك ←</a>
            @else
                <a href="{{ route('login') }}" class="w-full bg-orange-500 hover:bg-orange-400 text-white text-sm font-bold py-3 px-6 rounded-xl text-center">تسجيل الدخول ←</a>
            @endauth
            <a href="{{ route('home') }}" class="w-full border border-white/10 hover:border-white/20 text-white/60 hover:text-white text-sm py-3 px-6 rounded-xl text-center">العودة للرئيسية</a>
        </div>
    </div>
</div>

{{-- ══ SMART FORM ════════════════════════════════════════════════════════════ --}}
@else
<div class="max-w-4xl mx-auto space-y-3">

    @error('booking')
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-sm">{{ $message }}</div>
    @enderror

    {{-- ══ 0: اختر التصنيف ════════════════════════════════════════════════ --}}
    <div class="sbf-card" id="category-step">
        <button type="button" wire:click="toggleSection('category')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ $selectedCategoryId ? 'sbf-dot-done' : 'sbf-dot-active' }}">
                    @if($selectedCategoryId) ✓ @else 1 @endif
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الأولى</div>
                    <h2 class="text-sm font-bold text-white">ماذا تريد أن نساعدك فيه؟</h2>
                </div>
            </div>
            @if($selectedCategoryId)
            <div class="flex items-center gap-2">
                <span class="text-xs text-green-400 font-semibold">{{ $categories->firstWhere('id', $selectedCategoryId)?->name }}</span>
                <svg class="w-4 h-4 text-white/30" style="{{ $catOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            @endif
        </button>

        @if($catOpen)
        <div class="sbf-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach($categories as $cat)
                <button type="button" wire:click="selectCategory({{ $cat->id }})"
                    class="sbf-cat-card {{ $selectedCategoryId === $cat->id ? 'sbf-selected' : '' }}">
                    @if($selectedCategoryId === $cat->id)
                    <div style="position:absolute;top:8px;left:8px;width:20px;height:20px;background:#f97316;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#fff;">✓</div>
                    @endif
                    @if($cat->icon)<span style="font-size:2rem;display:block;margin-bottom:0.5rem;">{{ $cat->icon }}</span>@endif
                    <span class="font-bold text-sm text-white block mb-1">{{ $cat->name }}</span>
                    @if($cat->description)<span class="text-xs text-white/40 leading-relaxed">{{ Str::limit($cat->description, 60) }}</span>@endif
                </button>
                @endforeach
            </div>

            @if($recentWorks->isNotEmpty() && !$selectedCategoryId)
            <div class="mt-5 pt-5 border-t border-white/5">
                <p class="text-[10px] text-white/30 mb-3 text-center">من أحدث أعمالنا</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($recentWorks as $work)
                    <div style="aspect-ratio:16/9;border-radius:0.5rem;overflow:hidden;background:rgba(255,255,255,0.05);position:relative;">
                        @if($work->youtube_video_id)
                            <img src="{{ $work->youtube_thumbnail }}" alt="{{ $work->title }}" style="width:100%;height:100%;object-fit:cover;opacity:0.5;">
                        @elseif($work->image_path)
                            <img src="{{ Storage::url($work->image_path) }}" alt="{{ $work->title }}" style="width:100%;height:100%;object-fit:cover;opacity:0.5;">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ══ 1: اختر الخدمة ════════════════════════════════════════════════ --}}
    @if($showServiceStep)
    <div class="sbf-card" id="service-step">
        <button type="button" wire:click="toggleSection('service')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ $selectedServiceId ? 'sbf-dot-done' : 'sbf-dot-active' }}">
                    @if($selectedServiceId) ✓ @else 2 @endif
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الثانية</div>
                    <h2 class="text-sm font-bold text-white">اختر الخدمة</h2>
                </div>
            </div>
            @if($selectedServiceId && $svc)
            <div class="flex items-center gap-2">
                <span class="text-xs text-green-400 font-semibold">{{ $svc->name }}</span>
                <svg class="w-4 h-4 text-white/30" style="{{ $svcOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            @endif
        </button>

        @if($svcOpen)
        <div class="sbf-body">
            @if($services->isEmpty())
            <div class="py-8 text-center text-white/40 text-sm border-2 border-dashed border-white/10 rounded-xl">
                <span class="text-2xl block mb-2">🔌</span>لا توجد خدمات متاحة حالياً
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($services as $svcItem)
                <button type="button" wire:click="selectService({{ $svcItem->id }})"
                    class="sbf-svc-card {{ $selectedServiceId === $svcItem->id ? 'sbf-selected' : '' }}">
                    @if($selectedServiceId === $svcItem->id)
                    <div style="position:absolute;top:8px;left:8px;width:20px;height:20px;background:#f97316;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#fff;">✓</div>
                    @endif
                    <div class="flex items-start gap-3">
                        @if($svcItem->icon)<span style="font-size:1.25rem;opacity:0.8;flex-shrink:0;">{{ $svcItem->icon }}</span>@endif
                        <div class="text-right flex-grow">
                            <div class="font-bold text-sm text-white mb-1">{{ $svcItem->name }}</div>
                            @if($svcItem->description)<div class="text-xs text-white/40 leading-relaxed line-clamp-2">{{ Str::limit($svcItem->description, 80) }}</div>@endif
                            @if($svcItem->base_price > 0)<div class="mt-1 text-xs text-orange-400 font-semibold">يبدأ من {{ number_format($svcItem->base_price, 0) }} دج</div>@endif
                        </div>
                    </div>
                </button>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- ══ 2: اختر الباقة ════════════════════════════════════════════════ --}}
    @if($showPackageStep && $svc)
    <div class="sbf-card" id="package-step">
        <button type="button" wire:click="toggleSection('package')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ ($selectedPackageId || $isCustomPackage) ? 'sbf-dot-done' : 'sbf-dot-active' }}">
                    @if($selectedPackageId || $isCustomPackage) ✓ @else 3 @endif
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الثالثة</div>
                    <h2 class="text-sm font-bold text-white">@if($isSub) اختر خطة الاشتراك @else ما الذي يناسب مناسبتك؟ @endif</h2>
                </div>
            </div>
            @if($selectedPackageId || $isCustomPackage)
            <div class="flex items-center gap-2">
                <span class="text-xs text-green-400 font-semibold">{{ $pkgName }}</span>
                <svg class="w-4 h-4 text-white/30" style="{{ $pkgOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            @endif
        </button>

        @if($pkgOpen)
        <div class="sbf-body" style="display:flex;flex-direction:column;gap:1rem;">
            @error('package')<div class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg p-3">{{ $message }}</div>@enderror

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($packages as $pkg)
                <button type="button" wire:click="selectPackage({{ $pkg->id }})"
                    class="sbf-pkg-card {{ $selectedPackageId === $pkg->id && !$isCustomPackage ? 'sbf-selected' : '' }}">
                    @if($pkg->is_featured)
                    <div style="position:absolute;top:-8px;right:16px;background:#f97316;color:#fff;font-size:9px;font-weight:700;padding:2px 8px;border-radius:999px;">⭐ الأكثر طلباً</div>
                    @endif
                    <div style="position:absolute;top:12px;left:12px;width:20px;height:20px;border-radius:50%;border:2px solid;display:flex;align-items:center;justify-content:center;
                        border-color:{{ $selectedPackageId === $pkg->id && !$isCustomPackage ? '#f97316' : 'rgba(255,255,255,0.2)' }};
                        background:{{ $selectedPackageId === $pkg->id && !$isCustomPackage ? '#f97316' : 'transparent' }};">
                        @if($selectedPackageId === $pkg->id && !$isCustomPackage)<span style="color:#fff;font-size:10px;">✓</span>@endif
                    </div>
                    <div class="font-bold text-sm text-white mb-1">{{ $pkg->name }}</div>
                    @if($pkg->subtitle)<div class="text-xs text-white/40 mb-2">{{ $pkg->subtitle }}</div>@endif
                    <div class="mb-3">
                        @if($pkg->old_price && $pkg->hasDiscount())<span class="text-xs text-white/30 line-through mr-1">{{ number_format($pkg->old_price, 0) }}</span>@endif
                        <span class="font-syne font-extrabold text-xl text-orange-500">{{ $pkg->price > 0 ? number_format($pkg->price, 0) : ($pkg->price_note ?? '—') }}</span>
                        @if($pkg->price > 0)<span class="text-xs text-orange-400 mr-1">دج{{ $isSub ? '/شهر' : '' }}</span>@endif
                    </div>
                    @if($pkg->features && count($pkg->features) > 0)
                    <ul style="display:flex;flex-direction:column;gap:4px;">
                        @foreach(array_slice($pkg->features, 0, 3) as $feat)
                        <li style="display:flex;align-items:flex-start;gap:8px;font-size:12px;color:rgba(255,255,255,0.5);">
                            <span style="color:#f97316;flex-shrink:0;margin-top:2px;">✓</span><span>{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @elseif($pkg->description)
                    <p class="text-xs text-white/40 leading-relaxed">{{ Str::limit($pkg->description, 80) }}</p>
                    @endif
                </button>
                @endforeach

                @if($svc->packages->where('is_buildable', true)->isNotEmpty())
                <button type="button" wire:click="enableCustomPackage"
                    class="sbf-pkg-card {{ $isCustomPackage ? 'sbf-selected' : '' }}" style="border-style:dashed;">
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1rem 0;text-align:center;">
                        <div style="width:40px;height:40px;border-radius:50%;border:2px solid;display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:1.25rem;
                            border-color:{{ $isCustomPackage ? '#f97316' : 'rgba(255,255,255,0.2)' }};
                            background:{{ $isCustomPackage ? '#f97316' : 'transparent' }};
                            color:{{ $isCustomPackage ? '#fff' : 'rgba(255,255,255,0.4)' }};">
                            {{ $isCustomPackage ? '✓' : '+' }}
                        </div>
                        <div class="font-bold text-sm text-white mb-1">باقة مخصصة</div>
                        <div class="text-xs text-white/40">اختر ما يناسبك فقط</div>
                    </div>
                </button>
                @endif
            </div>

            @if($isCustomPackage && $packageOptions->isNotEmpty())
            <div style="border-top:1px solid rgba(255,255,255,0.05);padding-top:1rem;">
                <div class="text-xs font-semibold text-white/60 mb-3">اختر الخيارات:</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($packageOptions as $opt)
                    <button type="button" wire:click="toggleOption({{ $opt->id }})"
                        style="display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.03);border:1px solid {{ isset($selectedOptions[$opt->id]) ? '#f97316' : 'rgba(255,255,255,0.1)' }};background:{{ isset($selectedOptions[$opt->id]) ? 'rgba(249,115,22,0.1)' : 'rgba(255,255,255,0.03)' }};border-radius:0.75rem;padding:12px;">
                        <div style="width:20px;height:20px;border:2px solid {{ isset($selectedOptions[$opt->id]) ? '#f97316' : 'rgba(255,255,255,0.15)' }};background:{{ isset($selectedOptions[$opt->id]) ? '#f97316' : 'transparent' }};border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if(isset($selectedOptions[$opt->id]))<span style="color:#fff;font-size:10px;">✓</span>@endif
                        </div>
                        <div style="flex-grow:1;text-align:right;">
                            <div class="text-xs font-bold text-white">{{ $opt->name }}</div>
                        </div>
                        <div class="text-xs font-bold text-orange-400 whitespace-nowrap">+{{ number_format($opt->price, 0) }} دج</div>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            @if(($selectedPackageId || $isCustomPackage) && $pricing['total'] > 0)
            <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(249,115,22,0.05);border:1px solid rgba(249,115,22,0.2);border-radius:0.75rem;padding:12px 16px;">
                <span class="text-xs text-white/60">التقدير الأولي</span>
                <span class="font-syne font-bold text-lg text-orange-500">{{ number_format($pricing['total'], 0) }} دج</span>
            </div>
            @endif

            @if($selectedPackageId || $isCustomPackage)
            <button type="button" wire:click="confirmPackage" class="sbf-btn-primary" style="width:100%;">تأكيد الاختيار والمتابعة ←</button>
            @endif

            @if($testimonials->isNotEmpty() && !$selectedPackageId && !$isCustomPackage)
            <div style="border-top:1px solid rgba(255,255,255,0.05);padding-top:1rem;">
                <p class="text-[10px] text-white/30 text-center mb-3">ماذا يقول عملاؤنا</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($testimonials as $t)
                    <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:0.75rem;padding:1rem;">
                        <div style="display:flex;gap:2px;margin-bottom:8px;">
                            @for($i = 1; $i <= 5; $i++)<span style="color:{{ $i <= $t->rating ? '#f97316' : 'rgba(255,255,255,0.2)' }};font-size:12px;">★</span>@endfor
                        </div>
                        <p class="text-xs text-white/60 leading-relaxed mb-3">"{{ Str::limit($t->content, 100) }}"</p>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:rgba(249,115,22,0.2);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fb923c;">
                                {{ $t->initial ?? Str::upper(Str::substr($t->client_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-white/70">{{ $t->client_name }}</div>
                                @if($t->client_role ?? false)<div class="text-[9px] text-white/30">{{ $t->client_role }}</div>@endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- ══ 3: التاريخ والوقت ══════════════════════════════════════════════ --}}
    @if($showDateStep)
    <div class="sbf-card" id="date-step">
        <button type="button" wire:click="toggleSection('date')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ $eventDate ? 'sbf-dot-done' : 'sbf-dot-active' }}">@if($eventDate) ✓ @else 4 @endif</div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الرابعة</div>
                    <h2 class="text-sm font-bold text-white">متى موعدك؟</h2>
                </div>
            </div>
            @if($eventDate)
            <div class="flex items-center gap-2">
                <span class="text-xs text-green-400 font-semibold">{{ \Carbon\Carbon::parse($eventDate)->translatedFormat('j F Y') }}</span>
                <svg class="w-4 h-4 text-white/30" style="{{ $dateOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            @endif
        </button>

        @if($dateOpen)
        <div class="sbf-body" style="display:flex;flex-direction:column;gap:1rem;">
            @error('eventDate')<div class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg p-3">{{ $message }}</div>@enderror

            @php
                $dayNames    = ['ح','ن','ث','ر','خ','ج','س'];
                $firstDay    = \Carbon\Carbon::create($calYear, $calMonth, 1);
                $daysInMonth = $firstDay->daysInMonth;
                $startDow    = ($firstDay->dayOfWeek + 1) % 7;
                $today       = \Carbon\Carbon::today();
            @endphp
            <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:1rem;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.05);">
                    <button type="button" wire:click="nextMonth" class="sbf-cal-nav">←</button>
                    <span class="text-sm font-bold text-white">{{ \Carbon\Carbon::create($calYear, $calMonth)->translatedFormat('F Y') }}</span>
                    <button type="button" wire:click="prevMonth" class="sbf-cal-nav">→</button>
                </div>
                <div style="display:grid;grid-template-columns:repeat(7,1fr);border-bottom:1px solid rgba(255,255,255,0.05);">
                    @foreach($dayNames as $d)<div style="padding:8px 0;text-align:center;font-size:10px;color:rgba(255,255,255,0.3);font-weight:600;">{{ $d }}</div>@endforeach
                </div>
                <div style="display:grid;grid-template-columns:repeat(7,1fr);padding:8px;gap:4px;">
                    @for($i = 0; $i < $startDow; $i++)<div></div>@endfor
                    @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dateStr = \Carbon\Carbon::create($calYear, $calMonth, $d)->format('Y-m-d');
                        $isPast  = \Carbon\Carbon::create($calYear, $calMonth, $d)->startOfDay()->lt($today);
                        $isSel   = $eventDate === $dateStr;
                    @endphp
                    <button type="button"
                        @if(!$isPast) wire:click="selectDate('{{ $dateStr }}')" @endif
                        style="aspect-ratio:1;border-radius:0.5rem;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:center;transition:all 0.15s;
                            @if($isPast) color:rgba(255,255,255,0.2);cursor:not-allowed;
                            @elseif($isSel) background:#f97316;color:#fff;
                            @else color:rgba(255,255,255,0.6);cursor:pointer; @endif"
                        @if($isPast) disabled @endif
                        @if(!$isPast && !$isSel) onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'" @endif
                        >{{ $d }}</button>
                    @endfor
                </div>
            </div>

            @if($dateStatus)
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;padding:8px 12px;border-radius:0.5rem;
                @if($dateStatus === 'available') background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);
                @elseif($dateStatus === 'pending') background:rgba(234,179,8,0.1);color:#facc15;border:1px solid rgba(234,179,8,0.2);
                @else background:rgba(239,68,68,0.1);color:#f87171;border:1px solid rgba(239,68,68,0.2); @endif">
                {{ $dateStatus === 'available' ? '✓ التاريخ متاح' : ($dateStatus === 'pending' ? '⏳ هناك طلب قيد المراجعة' : '✗ التاريخ محجوز') }}
            </div>
            @endif

            @if($eventDate && $dateStatus === 'available')
            <div style="display:flex;flex-direction:column;gap:12px;">
                @if($isAppt)
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="text-xs text-white/40 mb-1.5 block">وقت الموعد</label>
                        <input type="time" wire:model.live="slotStart" class="sbf-input" style="width:100%;" dir="ltr">
                        @error('slotStart')<p class="text-[10px] text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-white/40 mb-1.5 block">ينتهي (تلقائي)</label>
                        <input type="time" wire:model="slotEnd" class="sbf-input" style="width:100%;opacity:0.5;" dir="ltr" readonly>
                    </div>
                </div>
                @else
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="text-xs text-white/40 mb-1.5 block">وقت البداية</label>
                        <input type="time" wire:model.live="startTime" class="sbf-input" style="width:100%;" dir="ltr">
                        @error('startTime')<p class="text-[10px] text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-white/40 mb-1.5 block">وقت النهاية</label>
                        <input type="time" wire:model.live="endTime" class="sbf-input" style="width:100%;" dir="ltr">
                        @error('endTime')<p class="text-[10px] text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                @if($pricing['time_cost'] > 0)
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;background:rgba(249,115,22,0.05);border:1px solid rgba(249,115,22,0.2);border-radius:0.5rem;padding:8px 12px;">
                    <span class="text-white/60">رسوم الوقت الإضافي</span>
                    <span class="text-orange-400 font-bold">+{{ number_format($pricing['time_cost'], 0) }} دج</span>
                </div>
                @endif
                @endif
            </div>
            @endif

            @if($eventDate && ($isAppt ? $slotStart : ($startTime && $endTime)))
            <button type="button" wire:click="confirmDate" class="sbf-btn-primary" style="width:100%;">تأكيد الموعد والمتابعة ←</button>
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- ══ 4: الموقع ══════════════════════════════════════════════════════ --}}
    @if($showLocationStep && $svc && ($svc->show_wilaya_selector || $svc->show_venue_selector))
    <div class="sbf-card" id="location-step">
        <button type="button" wire:click="toggleSection('location')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ ($venueId || $venueCustom) ? 'sbf-dot-done' : 'sbf-dot-active' }}">@if($venueId || $venueCustom) ✓ @else 5 @endif</div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الخامسة</div>
                    <h2 class="text-sm font-bold text-white">أين ستقام الفعالية؟</h2>
                </div>
            </div>
            <svg class="w-4 h-4 text-white/30" style="{{ $locOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        @if($locOpen)
        <div class="sbf-body" style="display:flex;flex-direction:column;gap:1rem;">
            @error('venue')<div class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg p-3">{{ $message }}</div>@enderror

            @if($svc->show_wilaya_selector)
            <div>
                <label class="text-xs text-white/40 mb-2 block">الولاية</label>
                <select wire:model.live="wilayaId" class="sbf-input" style="width:100%;">
                    <option value="">اختر الولاية...</option>
                    @foreach($wilayas as $w)<option value="{{ $w->id }}">{{ $w->code }} - {{ $w->name }}</option>@endforeach
                </select>
                @error('wilayaId')<p class="text-[10px] text-red-400 mt-1">{{ $message }}</p>@enderror
                @if($pricing['travel_cost'] > 0)
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;background:rgba(249,115,22,0.05);border:1px solid rgba(249,115,22,0.2);border-radius:0.5rem;padding:8px 12px;margin-top:8px;">
                    <span class="text-white/60">رسوم التنقل</span>
                    <span class="text-orange-400 font-bold">+{{ number_format($pricing['travel_cost'], 0) }} دج</span>
                </div>
                @endif
            </div>
            @endif

            @if($svc->show_venue_selector)
            <div>
                <label class="text-xs text-white/40 mb-2 block">القاعة</label>
                @if($venues->isNotEmpty())
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;max-height:12rem;overflow-y:auto;">
                    @foreach($venues as $v)
                    <button type="button" wire:click="$set('venueId', {{ $v->id }})"
                        style="display:flex;align-items:center;gap:12px;text-align:right;background:rgba(255,255,255,0.02);border:1px solid {{ $venueId === $v->id ? '#f97316' : 'rgba(255,255,255,0.1)' }};background:{{ $venueId === $v->id ? 'rgba(249,115,22,0.05)' : 'rgba(255,255,255,0.02)' }};border-radius:0.75rem;padding:12px 16px;">
                        <div style="flex-grow:1;">
                            <div class="text-xs font-bold text-white">{{ $v->name }}</div>
                        </div>
                        <div style="width:16px;height:16px;border-radius:50%;border:2px solid {{ $venueId === $v->id ? '#f97316' : 'rgba(255,255,255,0.2)' }};background:{{ $venueId === $v->id ? '#f97316' : 'transparent' }};flex-shrink:0;"></div>
                    </button>
                    @endforeach
                </div>
                @endif
                <input type="text" wire:model="venueCustom" class="sbf-input" style="width:100%;" placeholder="أو اكتب اسم المكان يدوياً...">
            </div>
            @endif

            <button type="button" wire:click="confirmLocation" class="sbf-btn-primary" style="width:100%;">تأكيد الموقع والمتابعة ←</button>
        </div>
        @endif
    </div>
    @endif

    {{-- ══ 5: بيانات التواصل ══════════════════════════════════════════════ --}}
    @if($showContactStep)
    <div class="sbf-card" id="contact-step">
        <button type="button" wire:click="toggleSection('contact')" class="sbf-header">
            <div class="flex items-center gap-3">
                <div class="sbf-dot {{ ($name && $email && $phone) ? 'sbf-dot-done' : 'sbf-dot-active' }}">
                    @if($name && $email && $phone) ✓ @else {{ $isSub ? '3' : ($showLocationStep ? '6' : '5') }} @endif
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">الخطوة الأخيرة</div>
                    <h2 class="text-sm font-bold text-white">كيف نتواصل معك؟</h2>
                </div>
            </div>
            <svg class="w-4 h-4 text-white/30" style="{{ $conOpen ? 'transform:rotate(180deg)' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        @if($conOpen)
        <div class="sbf-body">
            @include('livewire.booking.partials._contact-form')
        </div>
        @endif
    </div>
    @endif

    {{-- ══ 6: الملخص + تأكيد ══════════════════════════════════════════════ --}}
    @if($showSummaryStep)
    @php
        $venueName  = $venueId ? ($venues->firstWhere('id', $venueId)?->name) : ($venueCustom ?: null);
        $wilayaName = $wilayaId ? ($wilayas->firstWhere('id', $wilayaId)?->name) : null;
    @endphp
    <div class="sbf-card" id="summary-step" style="border-color:rgba(249,115,22,0.3);">
        <div class="sbf-header" style="cursor:default;">
            <div class="flex items-center gap-3">
                <div class="sbf-dot sbf-dot-active">📋</div>
                <div class="text-right">
                    <div class="text-[10px] text-white/40">مراجعة</div>
                    <h2 class="text-sm font-bold text-white">ملخص حجزك</h2>
                </div>
            </div>
        </div>
        <div class="sbf-body">
            @include('livewire.booking.partials._summary-box', [
                'pricing'     => $pricing,
                'packageName' => $pkgName,
                'eventDate'   => $eventDate ?: null,
                'startTime'   => $isAppt ? $slotStart : $startTime,
                'endTime'     => $isAppt ? $slotEnd   : $endTime,
                'promoResult' => $promoResult,
                'showDeposit' => true,
                'isReview'    => true,
                'venueName'   => $venueName ?? null,
                'wilayaName'  => $wilayaName ?? null,
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone,
            ])

            @error('booking')
            <div class="mt-4 bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-xl text-xs">{{ $message }}</div>
            @enderror

            <button type="button"
                wire:click="submitBooking"
                wire:loading.attr="disabled"
                class="sbf-btn-primary"
                style="width:100%;margin-top:1rem;font-size:1rem;padding:1rem;box-shadow:0 0 30px rgba(249,115,22,0.3);">
                <span wire:loading.remove wire:target="submitBooking">🎉 تأكيد الحجز الآن</span>
                <span wire:loading wire:target="submitBooking" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg style="animation:spin 1s linear infinite;width:16px;height:16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    جاري الإرسال...
                </span>
            </button>
            <p class="text-[10px] text-white/30 text-center mt-3">بالضغط على تأكيد الحجز، أنت توافق على شروط الخدمة وسياسة الإلغاء</p>
        </div>
    </div>
    @endif

</div>
@endif

{{-- ══ STYLES ══════════════════════════════════════════════════════════════ --}}
@push('styles')
<style>
.sbf-card { background:rgba(255,255,255,0.03); backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.05); border-radius:1rem; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.3); }
.sbf-header { width:100%; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; transition:background 0.15s; cursor:pointer; background:transparent; border:none; }
.sbf-header:hover { background:rgba(255,255,255,0.02); }
.sbf-body { border-top:1px solid rgba(255,255,255,0.05); padding:1.25rem; }
.sbf-dot { width:2rem; height:2rem; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0; }
.sbf-dot-active { background:rgba(255,255,255,0.04); border:2px solid #f97316; color:#f97316; box-shadow:0 0 12px rgba(249,115,22,0.5); }
.sbf-dot-done { background:#f97316; color:#fff; box-shadow:0 0 8px rgba(234,88,12,0.5); }
.sbf-cat-card { position:relative; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:0.75rem; padding:1.25rem; text-align:center; cursor:pointer; transition:all 0.25s; display:block; width:100%; }
.sbf-cat-card:hover { border-color:rgba(249,115,22,0.5); background:rgba(255,255,255,0.05); }
.sbf-cat-card.sbf-selected { border-color:#f97316; background:rgba(249,115,22,0.05); box-shadow:0 0 20px rgba(249,115,22,0.15); }
.sbf-svc-card { position:relative; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:0.75rem; padding:1rem; cursor:pointer; transition:all 0.2s; display:block; width:100%; text-align:right; }
.sbf-svc-card:hover { border-color:rgba(255,255,255,0.2); }
.sbf-svc-card.sbf-selected { border-color:#f97316; background:rgba(249,115,22,0.05); }
.sbf-pkg-card { position:relative; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:1rem; padding:1.25rem; text-align:right; cursor:pointer; transition:all 0.3s; display:block; width:100%; }
.sbf-pkg-card:hover { border-color:rgba(255,255,255,0.2); }
.sbf-pkg-card.sbf-selected { border-color:#f97316; background:rgba(249,115,22,0.05); box-shadow:0 0 20px rgba(249,115,22,0.12); }
.sbf-input { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; color:#fff; outline:none; transition:border-color 0.2s; -webkit-appearance:none; appearance:none; }
.sbf-input:focus { border-color:#f97316; box-shadow:0 0 0 2px rgba(249,115,22,0.2); }
.sbf-input option { background:#111; color:#fff; }
.sbf-btn-primary { background:#f97316; color:#fff; font-weight:700; padding:0.75rem 1.5rem; border-radius:0.75rem; transition:all 0.2s; text-align:center; display:block; font-size:0.875rem; border:none; cursor:pointer; width:100%; }
.sbf-btn-primary:hover { background:#fb923c; }
.sbf-btn-primary:disabled { opacity:0.5; cursor:not-allowed; }
.sbf-cal-nav { width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.05); border:none; color:rgba(255,255,255,0.6); cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; transition:all 0.15s; }
.sbf-cal-nav:hover { background:rgba(255,255,255,0.1); color:#fff; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

{{-- ══ SCRIPTS ═════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('scroll-to', (params) => {
        const target = params?.target ?? (Array.isArray(params) ? params[0]?.target : null);
        if (!target) return;
        setTimeout(() => {
            const el = document.getElementById(target);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 200);
    });
});
</script>
@endpush