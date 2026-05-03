<div class="max-w-3xl mx-auto md:px-0">

@if($errors->has('booking'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 text-sm backdrop-blur-md">
        {{ $errors->first('booking') }}
    </div>
@endif

{{-- Stepper --}}
@php
    $wizardSteps = [['label' => 'الخدمة'], ['label' => 'الموعد'], ['label' => 'البيانات'], ['label' => 'المراجعة']];
@endphp
@include('livewire.booking.partials._stepper', ['steps' => $wizardSteps, 'currentStep' => $currentStep])

{{-- ══ STEP 1: PACKAGE SELECTION ══ --}}
@if($currentStep === 1)
<div class="animate-fade-in-up">
    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-8 relative overflow-hidden shadow-2xl shadow-black/50">
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-orange-500/10 rounded-full blur-[80px]"></div>

        <div class="flex items-center gap-3 mb-2 relative z-10">
            <button type="button" wire:click="prevStep" class="hidden md:flex w-8 h-8 items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none">&rarr;</button>
            <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
            <h2 class="text-xl font-bold text-white">اختر الخدمة</h2>
        </div>
        <p class="text-sm text-white/60 mb-8 ms-14 hidden md:block">اختر الباقة المناسبة لموعدك</p>

        @php $activePackages = $service->packages->where('is_active', true); @endphp
        
        @if($activePackages->isEmpty())
            <div class="bg-white/[0.04] border border-white/10 rounded-2xl p-8 text-center mb-6">
                <div class="w-16 h-16 bg-white/[0.04] border border-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-white/40">
                    <i class="fas fa-box-open text-2xl"></i>
                </div>
                <h3 class="text-white font-bold mb-2">لا توجد باقات متاحة حالياً</h3>
                <p class="text-white/40 text-sm">عذراً، لا تتوفر باقات نشطة لهذه الخدمة في الوقت الحالي. يرجى المحاولة لاحقاً أو التواصل مع الدعم.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6 relative z-10">
                @foreach($activePackages as $package)
                    <button type="button" wire:click="selectPackage({{ $package->id }})" 
                            class="relative group bg-white/[0.03] border rounded-2xl p-5 text-right transition-all duration-300 focus:outline-none overflow-hidden {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500/5 shadow-[0_0_20px_rgba(249,115,22,0.15)] transform -translate-y-1' : 'border-white/10 hover:border-white/20' }}">
                        <div class="absolute top-4 left-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500' : 'border-white/15 group-hover:border-white/30' }}">
                            @if($selectedPackageId === $package->id)<span class="text-white text-xs">✓</span>@endif
                        </div>
                        
                        <div class="font-syne font-bold text-sm text-white mb-1">{{ $package->name }}</div>
                        @if($package->price !== null)
                            <div class="font-syne font-extrabold text-2xl text-orange-500 mb-1">
                                {{ number_format($package->price, 0) }} <span class="text-xs text-orange-400">دج</span>
                            </div>
                        @else
                            <div class="text-sm font-bold text-white/60 mb-2">{{ $package->price_note ?? 'حسب الطلب' }}</div>
                        @endif
                        
                        @if($package->duration)
                            <div class="text-[10px] text-orange-300 mb-3">{{ $package->duration }} دقيقة</div>
                        @endif
                        
                        @if($package->description)
                            <div class="text-xs text-white/60 leading-relaxed">{{ $package->description }}</div>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif

        @error('package')<div class="text-xs text-red-500 mt-2 text-right">{{ $message }}</div>@enderror

        @if($pricing['base'] > 0)
            @include('livewire.booking.partials._summary-box', ['pricing' => $pricing, 'packageName' => null, 'eventDate' => null, 'startTime' => null, 'endTime' => null, 'promoResult' => null])
        @endif

        <div class="pt-8 border-t border-white/5 mt-8 flex justify-between items-center relative z-10 w-full gap-4">
            <button type="button" wire:click="nextStep" class="w-full md:w-auto bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] hover:shadow-[0_6px_20px_rgba(234,88,12,0.4)] md:ms-auto">
                التالي — حدد الموعد ←
            </button>
        </div>
    </div>
</div>

{{-- ══ STEP 2: DATE & SLOT ══ --}}
@elseif($currentStep === 2)
<div class="animate-fade-in-up">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-8 bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-8 shadow-2xl shadow-black/50">
        
        <div class="md:col-span-3 flex flex-col" x-data="{
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            get daysInMonth() { return new Date(this.currentYear, this.currentMonth + 1, 0).getDate() },
            get firstDayOfWeek() { return new Date(this.currentYear, this.currentMonth, 1).getDay() },
            get monthName() {
                return new Date(this.currentYear, this.currentMonth).toLocaleDateString('ar-DZ', { month: 'long', year: 'numeric' })
            },
            prevMonth() {
                if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear-- }
                else this.currentMonth--
            },
            nextMonth() {
                if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++ }
                else this.currentMonth++
            },
            selectDay(day) {
                let m = String(this.currentMonth + 1).padStart(2, '0');
                let d = String(day).padStart(2, '0');
                $wire.set('appointmentDate', this.currentYear + '-' + m + '-' + d);
            },
            isSelected(day) {
                if (!$wire.appointmentDate) return false;
                let m = String(this.currentMonth + 1).padStart(2, '0');
                let d = String(day).padStart(2, '0');
                return $wire.appointmentDate === this.currentYear + '-' + m + '-' + d;
            },
            isToday(day) {
                let t = new Date();
                return day === t.getDate() && this.currentMonth === t.getMonth() && this.currentYear === t.getFullYear();
            },
            isPast(day) {
                let t = new Date(); t.setHours(0,0,0,0);
                return new Date(this.currentYear, this.currentMonth, day) < t;
            }
        }">
            <div class="flex items-center justify-between mb-8">
                 <div class="flex items-center gap-3">
                    <button type="button" wire:click="prevStep" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none">&rarr;</button>
                    <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                    <h2 class="text-xl font-bold text-white">اختر الموعد</h2>
                </div>
            </div>

            @if($dateStatus === 'available')
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold rounded-full mb-4 self-start">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> التاريخ متاح
                </div>
            @elseif($dateStatus === 'booked')
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold rounded-full mb-4 self-start">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> غير متاح
                </div>
            @elseif($dateStatus === 'pending')
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-500 text-xs font-bold rounded-full mb-4 self-start">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> معلق
                </div>
            @endif
            @error('appointmentDate') <div class="text-xs text-red-500 mb-4">{{ $message }}</div> @enderror

            {{-- Calendar UI --}}
            <div class="bg-white/[0.03] rounded-2xl p-4 border border-white/10 flex-grow">
                <div class="flex justify-between items-center mb-4 px-2">
                    <div class="flex gap-2">
                        <button type="button" @click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/[0.04] text-white hover:bg-orange-400 transition-colors cursor-pointer">›</button>
                        <button type="button" @click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/[0.04] text-white hover:bg-orange-400 transition-colors cursor-pointer">‹</button>
                    </div>
                    <div class="font-syne font-bold text-white text-lg" x-text="monthName"></div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">أح</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">إث</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">ثل</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">أر</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">خم</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">جم</div>
                    <div class="text-[10px] text-white/40 font-bold tracking-wider py-1">سب</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center">
                    <template x-for="i in firstDayOfWeek" :key="'e'+i">
                        <div class="p-2"></div>
                    </template>
                    <template x-for="day in daysInMonth" :key="day">
                        <button type="button" 
                            class="relative py-2 px-1 text-sm rounded-xl transition-all focus:outline-none"
                            :class="{
                               'text-white/20 cursor-not-allowed opacity-50': isPast(day),
                               'bg-orange-500 text-white font-bold shadow-lg shadow-orange-500/40 scale-105': !isPast(day) && isSelected(day),
                               'text-white/80 hover:bg-white/[0.08] cursor-pointer': !isPast(day) && !isSelected(day),
                               'border border-white/10': isToday(day) && !isSelected(day)
                            }"
                            @click="!isPast(day) && selectDay(day)">
                            <span x-text="day"></span>
                            <div x-show="!isPast(day) && !isSelected(day)" class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-orange-500/50 rounded-full"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Time Slot --}}
        <div class="md:col-span-2 flex flex-col">
            <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 mb-auto mt-16 md:mt-24">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-white/40"></div>
                    <h3 class="text-sm font-bold text-white">وقت الموعد</h3>
                </div>
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-xs text-white/60 mb-1">وقت البداية</label>
                        <input type="time" wire:model.live="slotStart" 
                               class="bg-white/[0.04] border border-white/10 rounded-xl px-4 py-2 w-full text-white focus:outline-none focus:border-orange-500 text-left" dir="ltr">
                        @error('slotStart') <div class="text-[10px] text-red-500 mt-1 text-right">{{ $message }}</div> @enderror
                    </div>
                    <div class="opacity-70">
                        <label class="block text-xs text-white/60 mb-1">وقت النهاية (تلقائي)</label>
                        <input type="time" wire:model="slotEnd" readonly 
                               class="bg-white/[0.04] border border-white/10 rounded-xl px-4 py-2 w-full text-white/40 text-left cursor-not-allowed" dir="ltr">
                        <div class="text-[10px] text-white/40 mt-1 text-right">مُحتسب آلياً بمدة {{ $durationMinutes }} دقيقة</div>
                    </div>
                </div>
            </div>
            
            <div class="pt-8 border-t border-white/5 mt-8 max-w-full">
                <button type="button" wire:click="nextStep" class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] text-center">
                    التالي ←
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══ STEP 3: CONTACT INFO ══ --}}
@elseif($currentStep === 3)
<div class="animate-fade-in-up">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-8">
            <div class="flex items-center gap-3 mb-8">
                <button type="button" wire:click="prevStep" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none">&rarr;</button>
                <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                <h2 class="text-xl font-bold text-white">البيانات</h2>
            </div>
            
            @include('livewire.booking.partials._contact-form', ['panelTitle' => 'معلومات الحجز'])
            
            <div class="pt-6 border-t border-white/5 mt-auto">
                <button type="button" wire:click="nextStep" class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] text-lg">
                    مراجعة الموعد ←
                </button>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="sticky top-24">
        @php $selPkg = $selectedPackageId ? \App\Models\Service\Package::find($selectedPackageId) : null; @endphp
                @include('livewire.booking.partials._summary-box', ['pricing'=>$pricing,'packageName'=>$selPkg?->name,'eventDate'=>$appointmentDate,'startTime'=>$slotStart,'endTime'=>$slotEnd,'promoResult'=>null])
            </div>
        </div>
    </div>
</div>

{{-- ══ STEP 4: REVIEW & SUBMIT ══ --}}
@elseif($currentStep === 4)
<div class="animate-fade-in-up">
    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-10 max-w-2xl mx-auto text-center">
        <div class="w-20 h-20 bg-white/[0.05] border border-orange-500/50 rounded-full flex items-center justify-center mx-auto shadow-[0_0_20px_rgba(249,115,22,0.3)] mb-6">
            <span class="text-3xl text-orange-500">📋</span>
        </div>
        <h2 class="font-syne font-bold text-3xl text-white mb-2">مراجعة الموعد</h2>
        <p class="text-white/60 text-sm mb-8">هل أنت متأكد من كافة البيانات المدخلة؟</p>

        @php $selPkg = $selectedPackageId ? \App\Models\Service\Package::find($selectedPackageId) : null; @endphp
        <div class="text-right">
            @include('livewire.booking.partials._summary-box', [
                'pricing' => $pricing,
                'packageName' => $selPkg?->name,
                'eventDate' => $appointmentDate,
                'startTime' => $slotStart,
                'endTime' => $slotEnd,
                'promoResult' => $promoResult,
                'showDeposit' => false,
                'isReview' => true,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
            ])
        </div>

        <div class="mt-8 pt-6 border-t border-white/5 space-y-4">
            <button type="button" wire:click="submitBooking" wire:loading.attr="disabled" 
                    class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] flex justify-center items-center h-14 text-lg">
                <span wire:loading.remove wire:target="submitBooking">تأكيد الموعد ←</span>
                <span wire:loading wire:target="submitBooking" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    جاري إرسال الموعد...
                </span>
            </button>
            <button type="button" wire:click="prevStep" class="w-full bg-transparent border border-white/10 text-white/60 hover:text-white hover:border-white/20 font-bold py-3 rounded-xl transition-colors text-sm">
                ← رجوع لتعديل البيانات
            </button>
        </div>
    </div>
</div>
@endif

</div>
