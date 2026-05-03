<div>
{{-- ════════════════════════════════════════════════════════ --}}
{{-- ONX Edge — EventBookingForm Livewire Component          --}}
{{-- ════════════════════════════════════════════════════════ --}}

@if($bookingComplete)
{{-- ══ STEP 5: CONFIRMATION ══ --}}
<div class="form-panel">
    <div class="confirm-box">
        <div class="confirm-circle">✓</div>
        <div class="confirm-title">تم استلام حجزك!</div>
        <div class="confirm-ref">#{{ str_pad($projectId, 4, '0', STR_PAD_LEFT) }}</div>
        <p class="confirm-sub">سنتواصل معك قريباً لتأكيد التفاصيل ومعلومات دفع العربون.</p>

        {{-- Deposit Warning --}}
        <div class="deposit-warn">
            <div class="dw-title">⚠️ حجزك غير مؤكد بعد</div>
            <div class="dw-desc">لتأكيد حجزك يجب دفع العربون. سيقوم الفريق بتأكيده خلال 24 ساعة من استلام العربون.</div>
            <div class="dw-amount">العربون: {{ number_format($pricingSummary['deposit'], 0) }} دج</div>
        </div>

        {{-- Credentials --}}
        @if($generatedPassword)
            <div class="creds-box">
                <div class="creds-title">🔑 بيانات دخولك — احفظها الآن</div>
                <div class="cred-row">
                    <span class="cred-label">البريد</span>
                    <span class="cred-val">{{ $email }}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">كلمة المرور</span>
                    <span class="cred-val">{{ $generatedPassword }}</span>
                </div>
                <div class="creds-once">⚠️ تظهر مرة واحدة فقط — لن تظهر مجدداً</div>
            </div>
        @elseif($existingAccount)
            <div class="creds-box" style="text-align:center">
                <div style="font-size:24px;margin-bottom:8px">👤</div>
                <div style="font-size:13px;font-weight:700;margin-bottom:4px">تم ربط الحجز بحسابك</div>
                <div style="font-size:11px;color:var(--mut2)">سجّل دخولك بياناتك المعتادة</div>
            </div>
        @endif

        {{-- Actions --}}
        @auth
            <a href="{{ route('client.dashboard') }}" class="btn-dashboard">ادخل للوحتك ←</a>
        @else
            <a href="{{ route('login') }}" class="btn-dashboard">سجّل دخولك ←</a>
        @endauth
        <a href="{{ route('home') }}" class="btn-home">العودة للرئيسية</a>
    </div>
</div>

@else
{{-- ══ BOOKING FORM ══ --}}
<div class="form-panel" x-data="{ mobileStep: @entangle('currentStep') }">

    <div class="form-panel-head">
        {{-- Steps bar (mobile) --}}
        <div class="steps-bar" style="display:none" x-show="window.innerWidth < 900">
            <div class="step-btn" :class="{ 'active': mobileStep === 1, 'done': mobileStep > 1 }">الباقة</div>
            <div class="step-btn" :class="{ 'active': mobileStep === 2, 'done': mobileStep > 2 }">الموعد</div>
            @if($service->show_venue_selector || $service->show_wilaya_selector)
            <div class="step-btn" :class="{ 'active': mobileStep === 3, 'done': mobileStep > 3 }">الموقع</div>
            @endif
            <div class="step-btn" :class="{ 'active': mobileStep >= 4 }">التأكيد</div>
        </div>

        <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:800">
            @if($currentStep === 1) اختر باقتك
            @elseif($currentStep === 2) حدد الموعد
            @elseif($currentStep === 3) الموقع
            @else بياناتك الشخصية
            @endif
        </div>
    </div>

    <div class="form-panel-body">

        {{-- Global error --}}
        @if($errors->has('booking'))
            <div class="alert-box">{{ $errors->first('booking') }}</div>
        @endif

        {{-- ══ STEP 1: PACKAGES ══ --}}
        @if($currentStep === 1)

            <div class="sec-label">الباقات الجاهزة</div>

            {{-- Package cards --}}
            <div class="packages-grid">
                @foreach($service->packages as $package)
                    <div class="pkg-card {{ $selectedPackageId === $package->id ? 'selected' : '' }}"
                         wire:click="selectPackage({{ $package->id }})">
                        <div class="pkg-check">✓</div>
                        <div class="pkg-name">{{ $package->name }}</div>
                        <div class="pkg-price">{{ number_format($package->price, 0) }}</div>
                        <div class="pkg-unit">دج</div>
                        @if($package->description)
                            <div class="pkg-desc">{{ $package->description }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Custom package toggle --}}
            <button class="custom-toggle {{ $isCustomPackage ? 'active' : '' }}"
                    wire:click="enableCustomPackage">
                {{ $isCustomPackage ? '✓ أبني باقتي الخاصة' : '+ أبني باقتي الخاصة' }}
            </button>

            {{-- Options (shown only in custom mode) --}}
            @if($isCustomPackage)
                <div class="sec-label">اختر الخيارات</div>
                <div class="options-grid">
                    @foreach($options as $option)
                        <div class="opt-card {{ isset($selectedOptions[$option->id]) ? 'selected' : '' }}"
                             wire:click="toggleOption({{ $option->id }})">
                            <div class="opt-check">{{ isset($selectedOptions[$option->id]) ? '✓' : '' }}</div>
                            <div>
                                <div class="opt-name">{{ $option->name }}</div>
                                <div class="opt-price">
                                    {{ number_format($option->price, 0) }} دج
                                    @if($option->pricing_type === 'per_unit') / وحدة @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @error('package')
                <div class="error-msg">⚠️ {{ $message }}</div>
            @enderror

            <div class="step-actions">
                <button class="btn-next" wire:click="nextStep">
                    التالي — الموعد ←
                </button>
            </div>

        {{-- ══ STEP 2: DATE & TIME ══ --}}
        @elseif($currentStep === 2)

            {{-- Date --}}
            <div class="date-field">
                <div class="field-label">تاريخ الفعالية</div>
                <input type="date"
                       class="field-input"
                       wire:model.live="eventDate"
                       min="{{ date('Y-m-d') }}">
                @if($checkingDate)
                    <div class="avail-badge avail-loading">⏳ جاري التحقق...</div>
                @elseif($dateAvailable === true)
                    <div class="avail-badge avail-ok">✓ التاريخ متاح</div>
                @elseif($dateAvailable === false)
                    <div class="avail-badge avail-no">✗ هذا التاريخ محجوز</div>
                @endif
                @error('eventDate')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Time --}}
            @if($service->time_mode === \App\Enums\TimeMode::Wedding)
                <div class="sec-label">أوقات الفعالية</div>
                <div class="time-grid">
                    <div>
                        <div class="field-label">وقت البداية</div>
                        <input type="time" class="field-input"
                               wire:model.live.debounce.500ms="startTime">
                        <div class="time-note">الافتراضي 19:00 — كل ساعة أبكر: +4,000 دج</div>
                    </div>
                    <div>
                        <div class="field-label">وقت النهاية</div>
                        <input type="time" class="field-input"
                               wire:model.live.debounce.500ms="endTime">
                        <div class="time-note">الافتراضي 04:00 — كل 30 دقيقة بعدها: +3,000 دج</div>
                    </div>
                </div>
                @if($pricingSummary['time_cost'] > 0)
                    <div class="time-cost">رسوم الوقت: {{ number_format($pricingSummary['time_cost'], 0) }} دج</div>
                @endif
            @else
                <div class="sec-label">وقت الجلسة</div>
                <div class="time-grid">
                    <div>
                        <div class="field-label">وقت البداية</div>
                        <input type="time" class="field-input"
                               wire:model.live.debounce.500ms="startTime">
                    </div>
                    <div>
                        <div class="field-label">وقت النهاية</div>
                        <input type="time" class="field-input"
                               wire:model.live.debounce.500ms="endTime">
                    </div>
                </div>
                @if($pricingSummary['time_cost'] > 0)
                    <div class="time-cost">رسوم إضافية: {{ number_format($pricingSummary['time_cost'], 0) }} دج</div>
                @endif
            @endif

            @error('startTime') <div class="error-msg">{{ $message }}</div> @enderror
            @error('endTime')   <div class="error-msg">{{ $message }}</div> @enderror

            <div class="step-actions">
                <button class="btn-prev" wire:click="prevStep">← رجوع</button>
                <button class="btn-next" wire:click="nextStep">التالي ←</button>
            </div>

        {{-- ══ STEP 3: LOCATION ══ --}}
        @elseif($currentStep === 3)

            @if($service->show_venue_selector)
                <div class="location-section">
                    <div class="field-label">القاعة</div>
                    <select class="field-input" wire:model.live="venueId">
                        <option value="">اختر قاعة...</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                    <div class="venue-or">أو</div>
                    <input type="text"
                           class="field-input"
                           placeholder="اكتب اسم القاعة يدوياً..."
                           wire:model.live.debounce.400ms="venueCustom">
                    @error('venue') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            @endif

            @if($service->show_wilaya_selector)
                <div class="location-section">
                    <div class="field-label">الولاية</div>
                    <select class="field-input" wire:model.live="wilayaId">
                        <option value="">اختر الولاية...</option>
                        @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya->id }}">{{ $wilaya->code }} — {{ $wilaya->name }}</option>
                        @endforeach
                    </select>
                    @if($pricingSummary['travel_cost'] > 0)
                        <div class="time-cost">رسوم التنقل: {{ number_format($pricingSummary['travel_cost'], 0) }} دج</div>
                    @elseif($wilayaId)
                        <div style="font-size:11px;color:#4ade80;margin-top:4px">✓ لا توجد رسوم تنقل</div>
                    @endif
                    @error('wilayaId') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            @endif

            <div class="step-actions">
                <button class="btn-prev" wire:click="prevStep">← رجوع</button>
                <button class="btn-next" wire:click="nextStep">التالي ←</button>
            </div>

        {{-- ══ STEP 4: PERSONAL INFO ══ --}}
        @elseif($currentStep === 4)

            <div class="sec-label">بياناتك الشخصية</div>

            <div class="info-grid" style="margin-bottom:12px">
                <div>
                    <div class="field-label">الاسم الكامل *</div>
                    <input type="text" class="field-input"
                           placeholder="اسمك"
                           wire:model="name">
                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div>
                    <div class="field-label">رقم الهاتف *</div>
                    <input type="tel" class="field-input"
                           placeholder="05xxxxxxxx"
                           wire:model="phone">
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-bottom:20px">
                <div class="field-label">البريد الإلكتروني *</div>
                <input type="email" class="field-input"
                       placeholder="example@email.com"
                       wire:model="email">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            {{-- Summary --}}
            <div style="background:var(--sur2);border:1px solid var(--bdr2);border-radius:14px;padding:16px;margin-bottom:8px">
                <div class="sec-label" style="margin-bottom:12px">ملخص الحجز</div>
                @if($selectedPackageId)
                    @php $pkg = $service->packages->find($selectedPackageId) @endphp
                    @if($pkg)
                        <div class="sum-row"><span class="sum-label">الباقة</span><span class="sum-val">{{ $pkg->name }}</span></div>
                    @endif
                @elseif($isCustomPackage)
                    <div class="sum-row"><span class="sum-label">الباقة</span><span class="sum-val">مخصصة</span></div>
                @endif
                @if($eventDate)
                    <div class="sum-row"><span class="sum-label">التاريخ</span><span class="sum-val">{{ \Carbon\Carbon::parse($eventDate)->format('d / m / Y') }}</span></div>
                @endif
                @if($pricingSummary['base'] > 0)
                    <div class="sum-row"><span class="sum-label">السعر الأساسي</span><span class="sum-val">{{ number_format($pricingSummary['base'], 0) }} دج</span></div>
                @endif
                @if($pricingSummary['options_cost'] > 0)
                    <div class="sum-row"><span class="sum-label">الخيارات</span><span class="sum-val">{{ number_format($pricingSummary['options_cost'], 0) }} دج</span></div>
                @endif
                @if($pricingSummary['time_cost'] > 0)
                    <div class="sum-row"><span class="sum-label">رسوم الوقت</span><span class="sum-val" style="color:var(--onx)">{{ number_format($pricingSummary['time_cost'], 0) }} دج</span></div>
                @endif
                @if($pricingSummary['travel_cost'] > 0)
                    <div class="sum-row"><span class="sum-label">رسوم التنقل</span><span class="sum-val" style="color:var(--onx)">{{ number_format($pricingSummary['travel_cost'], 0) }} دج</span></div>
                @endif
                <div class="sum-total" style="padding-top:12px">
                    <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700">الإجمالي</span>
                    <span style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--onx)">{{ number_format($pricingSummary['total'], 0) }} دج</span>
                </div>
                <div style="text-align:center;padding:10px;background:var(--dim);border-radius:8px;margin-top:8px">
                    <div style="font-size:10px;color:var(--mut2)">العربون المطلوب</div>
                    <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:var(--onx)">{{ number_format($pricingSummary['deposit'], 0) }} دج</div>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn-prev" wire:click="prevStep">← رجوع</button>
                <button class="btn-submit" wire:click="submitBooking" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submitBooking">تأكيد الحجز ←</span>
                    <span wire:loading wire:target="submitBooking">⏳ جاري الحجز...</span>
                </button>
            </div>

        @endif

    </div>
</div>
@endif

{{-- Dispatch pricing update to sidebar --}}
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.hook('commit', ({ component, succeed }) => {
        succeed(({ snapshot }) => {
            const data = component.data;
            if (data.pricingSummary) {
                // Get extra info
                const summary = { ...data.pricingSummary };
                if (data.eventDate) summary.event_date = data.eventDate;
                if (data.venueCustom) summary.venue_name = data.venueCustom;

                window.dispatchEvent(new CustomEvent('pricing-updated', { detail: summary }));
            }
        });
    });
});

window.addEventListener('pricing-updated', (e) => {
    if (typeof updateSummary === 'function') updateSummary(e.detail);
});
</script>
</div>
