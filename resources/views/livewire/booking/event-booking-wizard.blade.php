<div class="max-w-7xl mx-auto md:px-0 py-6">

@if($errors->has('booking'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 text-sm backdrop-blur-md">
        {{ $errors->first('booking') }}
    </div>
@endif

@php
    $pkgName = $selectedPackageId ? ($service->packages->firstWhere('id',$selectedPackageId)?->name) : ($isCustomPackage ? 'مخصصة' : null);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- MAIN CONTENT (2/3 on desktop) --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- SECTION 1: PACKAGE SELECTION (Collapsible) --}}
        <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden shadow-2xl shadow-black/50">
            
            {{-- Header --}}
            <button type="button" wire:click="togglePackageSection"
                    class="w-full px-6 md:px-8 py-6 md:py-8 flex items-center justify-between hover:bg-white/[0.02] transition-colors group">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                    <h2 class="text-lg md:text-xl font-bold text-white">اختر الخدمة والباقة</h2>
                </div>
                <div class="flex items-center gap-2 text-white/60 group-hover:text-white transition-colors">
                    @if($selectedPackageId || $isCustomPackage)
                        <span class="text-xs md:text-sm font-semibold text-green-400">✓ {{ $pkgName }}</span>
                    @endif
                    <svg class="w-5 h-5 transform transition-transform {{ $showPackageSection ? 'rotate-180' : '' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </button>

            {{-- Content --}}
            @if($showPackageSection)
            <div class="border-t border-white/5 px-6 md:px-8 py-6 md:py-8 space-y-6">
                
                {{-- Packages Grid --}}
                <div>
                    <p class="text-sm text-white/60 mb-4">اختر الباقة المناسبة لخدمة {{ $service->name }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($service->packages as $package)
                            <button type="button" wire:click="selectPackage({{ $package->id }})"
                                    class="relative group bg-white/[0.03] border rounded-2xl p-5 text-right transition-all duration-300 focus:outline-none overflow-hidden {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500/5 shadow-[0_0_20px_rgba(249,115,22,0.15)]' : 'border-white/10 hover:border-white/20' }}">
                                <div class="absolute top-4 left-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500' : 'border-white/15 group-hover:border-white/30' }}">
                                    @if($selectedPackageId === $package->id)<span class="text-white text-xs">✓</span>@endif
                                </div>
                                <div class="font-bold text-sm text-white mb-2">{{ $package->name }}</div>
                                <div class="font-syne font-extrabold text-2xl text-orange-500 mb-3">{{ number_format($package->price, 0) }} <span class="text-xs text-orange-400">دج</span></div>
                                @if($package->description)
                                    <div class="text-xs text-white/60 leading-relaxed line-clamp-2">{{ $package->description }}</div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Custom Package --}}
                <div>
                    <button type="button" wire:click="enableCustomPackage"
                            class="w-full relative group bg-white/[0.03] border-2 rounded-2xl p-5 flex items-center gap-4 transition-all duration-300 focus:outline-none {{ $isCustomPackage ? 'border-orange-500 bg-orange-500/5 shadow-[0_0_20px_rgba(249,115,22,0.15)]' : 'border-dashed border-white/10 hover:border-white/20' }}">
                        <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors {{ $isCustomPackage ? 'border-orange-500 bg-orange-500 text-white' : 'border-white/15 text-white/40 group-hover:text-white' }}">
                            <span class="text-xl font-light">{{ $isCustomPackage ? '✓' : '+' }}</span>
                        </div>
                        <div class="text-right flex-grow">
                            <strong class="block text-white text-sm">بناء باقة مخصصة</strong>
                            <span class="block text-xs text-white/40 mt-1">اختر العناصر الإضافية فقط</span>
                        </div>
                    </button>
                </div>

                {{-- Options for Custom Package --}}
                @if($isCustomPackage)
                    <div class="border-t border-white/5 pt-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1.5 h-1.5 rounded-full bg-white/30"></div>
                            <h3 class="text-sm font-bold text-white">الخيارات الإضافية</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($options as $option)
                                <button type="button" wire:click="toggleOption({{ $option->id }})"
                                        class="flex items-center gap-3 bg-white/[0.04] border rounded-xl p-3 transition-colors focus:outline-none {{ isset($selectedOptions[$option->id]) ? 'border-orange-500 bg-orange-500/10' : 'border-white/10 hover:border-white/15' }}">
                                    <div class="w-5 h-5 rounded border flex-shrink-0 flex items-center justify-center transition-colors {{ isset($selectedOptions[$option->id]) ? 'border-orange-500 bg-orange-500' : 'border-white/15' }}">
                                        @if(isset($selectedOptions[$option->id]))<span class="text-white text-[10px]">✓</span>@endif
                                    </div>
                                    <div class="flex-grow text-right">
                                        <div class="text-xs font-bold text-white">{{ $option->name }}</div>
                                    </div>
                                    <div class="text-xs font-bold text-orange-400 whitespace-nowrap">+{{ number_format($option->price, 0) }} دج@if($option->pricing_type==='per_unit')<span class="text-[10px]">/وحدة</span>@endif</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @error('package')<div class="text-xs text-red-500 text-right">{{ $message }}</div>@enderror
            </div>
            @endif
        </div>

        {{-- SECTION 2: DATE & TIME SELECTION --}}
        <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden shadow-2xl shadow-black/50 p-6 md:p-8">
            
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                    <h2 class="text-lg md:text-xl font-bold text-white">اختر الموعد والوقت</h2>
                </div>
                @if($eventDate && $dateStatus)
                    @if($dateStatus==='available')
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> متاح
                        </div>
                    @elseif($dateStatus==='booked')
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> غير متاح
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-500 text-xs font-bold rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> محجوز
                        </div>
                    @endif
                @endif
            </div>

            @error('eventDate')<div class="text-xs text-red-500 mb-4 text-right">{{ $message }}</div>@enderror

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Calendar (2 cols on desktop, 1 on mobile) --}}
                <div class="lg:col-span-2">
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $startOfMonth = \Carbon\Carbon::createFromDate($calYear, $calMonth, 1);
                        $daysInMonth  = $startOfMonth->daysInMonth;
                        $blankDays    = $startOfMonth->dayOfWeek;
                        $today        = \Carbon\Carbon::today()->format('Y-m-d');
                    @endphp
                    <div class="bg-white/[0.03] rounded-2xl p-4 border border-white/10">
                        <div class="flex justify-between items-center mb-4 px-2">
                            <div class="flex gap-2">
                                <button type="button" wire:click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/[0.04] text-white hover:bg-orange-400 transition-colors">›</button>
                                <button type="button" wire:click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/[0.04] text-white hover:bg-orange-400 transition-colors">‹</button>
                            </div>
                            <div class="font-bold text-white text-lg">{{ $startOfMonth->translatedFormat('F Y') }}</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-2">
                            @foreach(['أح','إث','ثل','أر','خم','جم','سب'] as $dn)
                                <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">{{ $dn }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            @for($i = 0; $i < $blankDays; $i++) <div class="p-2"></div> @endfor
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dateStr    = sprintf('%04d-%02d-%02d', $calYear, $calMonth, $d);
                                    $isPast     = $dateStr < $today;
                                    $isSelected = $dateStr === $eventDate;
                                    $isToday    = $dateStr === $today && !$isSelected;
                                @endphp
                                <button type="button"
                                        @if(!$isPast) wire:click="selectDate('{{ $dateStr }}')" @endif
                                        class="relative py-2 px-1 text-sm rounded-xl transition-all focus:outline-none
                                               {{ $isPast ? 'text-white/20 cursor-not-allowed opacity-50' : ($isSelected ? 'bg-orange-500 text-white font-bold shadow-lg shadow-orange-500/40 scale-105' : 'text-white/80 hover:bg-white/[0.08] cursor-pointer') }}
                                               {{ $isToday ? 'border border-white/10' : '' }}">
                                    {{ $d }}
                                    @if(!$isPast && !$isSelected)
                                        <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-orange-500/50 rounded-full"></div>
                                    @endif
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Time Inputs --}}
                <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-4 h-fit">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-white/40"></div>
                        <h3 class="text-sm font-bold text-white">وقت الفعالية</h3>
                    </div>
                    @php
                        $isWedding = $service->time_mode instanceof \App\Enums\TimeMode
                            ? $service->time_mode === \App\Enums\TimeMode::Wedding
                            : (string)$service->time_mode === 'wedding';
                    @endphp
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block text-xs text-white/60 mb-2">البداية</label>
                            <input type="time" wire:model.live.debounce.500ms="startTime"
                                   class="bg-white/[0.04] border border-white/10 rounded-xl px-3 py-2 w-full text-white focus:outline-none focus:border-orange-500 text-sm" dir="ltr">
                            @if($isWedding)<div class="text-[10px] text-white/40 mt-1 leading-tight">الافتراضي: 19:00</div>@endif
                        </div>
                        <div>
                            <label class="block text-xs text-white/60 mb-2">النهاية</label>
                            <input type="time" wire:model.live.debounce.500ms="endTime"
                                   @if($isWedding) readonly disabled @endif
                                   class="bg-white/[0.04] border border-white/10 rounded-xl px-3 py-2 w-full text-white focus:outline-none focus:border-orange-500 text-sm @if($isWedding) opacity-50 cursor-not-allowed @endif" dir="ltr">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: LOCATION & CONTACT DATA --}}
        <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden shadow-2xl shadow-black/50">
            
            <div class="px-6 md:px-8 py-6 md:py-8 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                    <h2 class="text-lg md:text-xl font-bold text-white">البيانات والموقع</h2>
                </div>
            </div>

            <div class="px-6 md:px-8 py-6 md:py-8 space-y-6">
                
                {{-- Location Section --}}
                @if($service->show_wilaya_selector || $service->show_venue_selector)
                <div class="space-y-4">
                    <h3 class="text-white font-semibold text-sm flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500/50"></span>
                        موقع الفعالية
                    </h3>
                    
                    @if($service->show_wilaya_selector)
                        <div>
                            <label class="block text-xs text-white/60 mb-2">الولاية</label>
                            <div class="relative">
                                <select wire:model.live="wilayaId" class="appearance-none bg-white/[0.04] border border-white/15 rounded-xl px-4 py-3 w-full text-white cursor-pointer focus:outline-none focus:border-orange-500 text-sm">
                                    <option value="" class="bg-neutral-900 text-white">-- اختر ولاية --</option>
                                    @foreach($wilayas as $wilaya)<option value="{{ $wilaya->id }}" class="bg-neutral-900 text-white">{{ $wilaya->code }} — {{ $wilaya->name }}</option>@endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-white/60">▼</div>
                            </div>
                            @error('wilayaId')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    @if($service->show_venue_selector)
                        <div>
                            <label class="block text-xs text-white/60 mb-2">اختر القاعة أو المساحة</label>
                            <div class="relative">
                                <select wire:model.live="venueId" class="appearance-none bg-white/[0.04] border border-white/15 rounded-xl px-4 py-3 w-full text-white cursor-pointer focus:outline-none focus:border-orange-500 text-sm {{ collect($venues)->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ collect($venues)->isEmpty() ? 'disabled' : '' }}>
                                    <option value="" class="bg-neutral-900 text-white">{{ collect($venues)->isEmpty() ? '-- لا توجد قاعات --' : '-- اختر --' }}</option>
                                    @foreach($venues as $venue)<option value="{{ $venue->id }}" class="bg-neutral-900 text-white">{{ $venue->name }}</option>@endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-white/60">▼</div>
                            </div>
                            <input type="text" wire:model.live.debounce.400ms="venueCustom" placeholder="أو أكتب المساحة يدوياً"
                                   class="mt-2 bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 w-full text-white focus:outline-none focus:border-orange-500 text-sm placeholder:text-white/30">
                            @error('venue')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>
                @endif

                {{-- Contact Form Section --}}
                <div class="border-t border-white/5 pt-6 space-y-4">
                    <h3 class="text-white font-semibold text-sm flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500/50"></span>
                        معلومات التواصل
                    </h3>
                    @include('livewire.booking.partials._contact-form')
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] text-center text-lg flex justify-center items-center">
            <span wire:loading.remove wire:target="nextStep">متابعة المراجعة والتأكيد ←</span>
            <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جاري...
            </span>
        </button>

    </div>{{-- end main content --}}

    {{-- STICKY SIDEBAR (1/3 on desktop, full on mobile) --}}
    <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-4">

            {{-- Booking Summary --}}
            <div class="bg-white/[0.04] backdrop-blur-md border border-white/5 rounded-2xl p-5 shadow-xl">
                <div class="text-sm font-bold text-white mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    الملخص
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-white/50">الخدمة</span>
                        <span class="font-bold text-white text-xs">{{ $service->name }}</span>
                    </div>
                    @if($pkgName)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">الباقة</span>
                            <span class="font-bold text-white text-xs">{{ $pkgName }}</span>
                        </div>
                    @endif
                    @if($eventDate)
                        <div class="flex justify-between items-start">
                            <span class="text-white/50">التاريخ</span>
                            <span class="font-bold text-white text-xs text-left">{{ \Carbon\Carbon::parse($eventDate)->translatedFormat('j F') }}</span>
                        </div>
                    @endif
                    @if($startTime && $endTime)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">الوقت</span>
                            <span class="font-bold text-white text-xs" dir="ltr">{{ $startTime }} — {{ $endTime }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pricing Summary --}}
            <div class="bg-white/[0.04] backdrop-blur-md border border-white/5 rounded-2xl p-5 shadow-xl">
                <div class="text-sm font-bold text-white mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    السعر
                </div>
                <div class="space-y-2 text-sm">
                    @if(($pricing['base'] ?? 0) > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">الأساسي</span>
                            <span class="text-white/80 text-xs">{{ number_format($pricing['base'], 0) }} دج</span>
                        </div>
                    @endif
                    @if(($pricing['options_cost'] ?? 0) > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">إضافيات</span>
                            <span class="text-white/80 text-xs">+{{ number_format($pricing['options_cost'], 0) }} دج</span>
                        </div>
                    @endif
                    @if(($pricing['time_cost'] ?? 0) > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">الوقت</span>
                            <span class="text-orange-400 text-xs">+{{ number_format($pricing['time_cost'], 0) }} دج</span>
                        </div>
                    @endif
                    @if(($pricing['travel_cost'] ?? 0) > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-white/50">التنقل</span>
                            <span class="text-orange-400 text-xs">+{{ number_format($pricing['travel_cost'], 0) }} دج</span>
                        </div>
                    @endif
                    <div class="h-px bg-white/10 my-3"></div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-white">الإجمالي</span>
                        <strong class="font-syne font-bold text-lg text-orange-500">
                            {{ number_format($pricing['total'] ?? 0, 0) }} <span class="text-xs text-orange-400">دج</span>
                        </strong>
                    </div>
                    @if(($pricing['deposit'] ?? 0) > 0)
                        <div class="bg-orange-500/10 border border-orange-500/20 rounded-xl p-3 mt-3 text-center">
                            <div class="text-[10px] text-white/50 mb-1">العربون</div>
                            <div class="font-bold text-sm text-orange-500">{{ number_format($pricing['deposit'], 0) }} دج</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>{{-- end sidebar --}}

</div>{{-- end grid --}}

</div>{{-- end main container --}}