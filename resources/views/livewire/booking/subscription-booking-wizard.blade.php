<div class="max-w-3xl mx-auto md:px-0">

@if($errors->has('booking'))
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 text-sm backdrop-blur-md">
        {{ $errors->first('booking') }}
    </div>
@endif

{{-- Stepper --}}
@php
    $wizardSteps = [['label' => 'الخطة'], ['label' => 'البيانات'], ['label' => 'المراجعة']];
@endphp
@include('livewire.booking.partials._stepper', ['steps' => $wizardSteps, 'currentStep' => $currentStep])

{{-- STEP 1: PLAN SELECTION --}}
@if($currentStep === 1)
<div class="animate-fade-in-up">
    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-8 relative overflow-hidden shadow-2xl shadow-black/50">
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-orange-500/10 rounded-full blur-[80px]"></div>

        <div class="flex items-center gap-3 mb-2 relative z-10">
            <button type="button" wire:click="prevStep" class="hidden md:flex w-8 h-8 items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none">&rarr;</button>
            <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
            <h2 class="text-xl font-bold text-white">اختر خطة الاشتراك</h2>
        </div>
        <p class="text-sm text-white/60 mb-8 ms-14 hidden md:block">اختر الخطة الدورية الأنسب لاحتياجات فريقك.</p>

        @php $activePackages = $service->packages->where('is_active', true); @endphp
        
        @if($activePackages->isEmpty())
            <div class="bg-white/[0.04] border border-white/10 rounded-2xl p-8 text-center mb-6">
                <div class="w-16 h-16 bg-white/[0.04] border border-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-white/40">
                    <i class="fas fa-box-open text-2xl"></i>
                </div>
                <h3 class="text-white font-bold mb-2">لا توجد خطط متاحة حالياً</h3>
                <p class="text-white/40 text-sm">عذراً، لا تتوفر خطط اشتراك نشطة لهذه الخدمة في الوقت الحالي. يرجى المحاولة لاحقاً أو التواصل مع الدعم.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6 relative z-10">
                @foreach($activePackages as $package)
                    <button type="button" wire:click="selectPlan({{ $package->id }})" 
                            class="relative group bg-white/[0.03] border rounded-2xl p-5 text-right transition-all duration-300 focus:outline-none overflow-hidden {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500/5 shadow-[0_0_20px_rgba(249,115,22,0.15)] transform -translate-y-1' : 'border-white/10 hover:border-white/20' }}">
                        @if($package->is_featured)
                            <div class="absolute top-0 right-0 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">الأكثر طلباً</div>
                        @endif
                        <div class="absolute top-4 left-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $selectedPackageId === $package->id ? 'border-orange-500 bg-orange-500' : 'border-white/15 group-hover:border-white/30' }}">
                            @if($selectedPackageId === $package->id)<span class="text-white text-xs">✓</span>@endif
                        </div>
                        
                        <div class="font-syne font-bold text-lg text-white mb-1 mt-4">{{ $package->name }}</div>
                        @if($package->subtitle)
                            <div class="text-xs text-white/40 mb-3">{{ $package->subtitle }}</div>
                        @endif
                        
                        @if($package->price !== null)
                            <div class="font-syne font-extrabold text-2xl text-orange-500 mb-1">
                                {{ number_format($package->price, 0) }} <span class="text-xs text-white/40 font-cairo">دج / شهر</span>
                            </div>
                            @if($package->old_price)
                                <div class="text-[11px] text-white/40 line-through mb-2">{{ number_format($package->old_price, 0) }} دج</div>
                            @endif
                        @else
                            <div class="text-sm text-white/60 font-bold mb-4">{{ $package->price_note ?? 'حسب الطلب' }}</div>
                        @endif
                        
                        @if($package->features)
                            <ul class="mt-4 space-y-2 border-t border-white/10 pt-4">
                                @foreach($package->features as $feature)
                                    <li class="flex items-start gap-2 text-xs text-white/60">
                                        <span class="text-orange-500 font-bold mt-0.5">✓</span> {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif

        @error('plan')<div class="text-xs text-red-500 mt-2 text-right">{{ $message }}</div>@enderror

        @if($pricing['base'] > 0)
            @include('livewire.booking.partials._summary-box', ['pricing' => $pricing, 'packageName' => null, 'eventDate' => null, 'startTime' => null, 'endTime' => null, 'promoResult' => null, 'billingLabel' => '/ شهر'])
        @endif

        <div class="pt-8 border-t border-white/5 mt-8 flex justify-between items-center relative z-10 w-full gap-4">
            <button type="button" wire:click="nextStep" class="w-full md:w-auto bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] hover:shadow-[0_6px_20px_rgba(234,88,12,0.4)] md:ms-auto">
                التالي — بياناتك ←
            </button>
        </div>
    </div>
</div>

{{-- STEP 2: CONTACT INFO --}}
@elseif($currentStep === 2)
<div class="animate-fade-in-up">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-8">
            <div class="flex items-center gap-3 mb-8">
                <button type="button" wire:click="prevStep" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/[0.06] border border-white/15 text-white/60 hover:text-white hover:border-orange-500 transition-colors focus:outline-none">&rarr;</button>
                <div class="w-2 h-2 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]"></div>
                <h2 class="text-xl font-bold text-white">البيانات</h2>
            </div>
            
            @include('livewire.booking.partials._contact-form', ['panelTitle' => 'معلومات الاشتراك'])
            
            <div class="pt-6 border-t border-white/5 mt-auto">
                <button type="button" wire:click="nextStep" class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] text-lg">
                    مراجعة الاشتراك ←
                </button>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="sticky top-24">
                @php $selectedPkg = $selectedPackageId ? \App\Models\Service\Package::find($selectedPackageId) : null; @endphp
                @include('livewire.booking.partials._summary-box', ['pricing'=>$pricing,'packageName'=>$selectedPkg?->name,'eventDate'=>null,'startTime'=>null,'endTime'=>null,'promoResult'=>null, 'billingLabel' => '/ شهر'])
            </div>
        </div>
    </div>
</div>

{{-- STEP 3: REVIEW & SUBMIT --}}
@elseif($currentStep === 3)
<div class="animate-fade-in-up">
    <div class="bg-white/[0.03] backdrop-blur-xl border border-white/5 rounded-[28px] p-6 md:p-10 max-w-2xl mx-auto text-center">
        <div class="w-20 h-20 bg-white/[0.05] border border-orange-500/50 rounded-full flex items-center justify-center mx-auto shadow-[0_0_20px_rgba(249,115,22,0.3)] mb-6">
            <span class="text-3xl text-orange-500">📋</span>
        </div>
        <h2 class="font-syne font-bold text-3xl text-white mb-2">مراجعة الاشتراك</h2>
        <p class="text-white/60 text-sm mb-8">هل أنت متأكد من كافة البيانات المدخلة؟</p>

        @php $selectedPkg = $selectedPackageId ? \App\Models\Service\Package::find($selectedPackageId) : null; @endphp
        
        <div class="text-right">
            @include('livewire.booking.partials._summary-box', [
                'pricing' => $pricing,
                'packageName' => $selectedPkg?->name,
                'eventDate' => null,
                'startTime' => null,
                'endTime' => null,
                'promoResult' => $promoResult,
                'showDeposit' => false,
                'isReview' => true,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'billingLabel' => '/ شهر',
            ])
        </div>

        <div class="mt-8 pt-6 border-t border-white/5 space-y-4">
            <button type="button" wire:click="submitBooking" wire:loading.attr="disabled" 
                    class="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(234,88,12,0.39)] flex justify-center items-center h-14 text-lg">
                <span wire:loading.remove wire:target="submitBooking">تأكيد الاشتراك ←</span>
                <span wire:loading wire:target="submitBooking" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    جاري الاشتراك...
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
