<?php

namespace App\Livewire\Booking;

use App\Models\Content\PortfolioItem;
use App\Models\Content\Testimonial;
use App\Models\Event\Venue;
use App\Models\Event\Wilaya;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * SmartBookingForm — نموذج حجز ذكي في صفحة واحدة
 *
 * يعرض الحقول تدريجياً حسب اختيارات المستخدم بدلاً من Wizard متعدد الخطوات.
 * كل اختيار يفتح القسم التالي بانتقال سلس.
 */
class SmartBookingForm extends Component
{
    // ── URL State (قابل للمشاركة) ────────────────────────────────
    #[Url(as: 'cat', except: '')]
    public ?int $selectedCategoryId = null;

    #[Url(as: 'svc', except: '')]
    public ?int $selectedServiceId = null;

    // ── Step visibility ──────────────────────────────────────────
    // كل خطوة تظهر بعد اكتمال السابقة
    public bool $showServiceStep   = false;
    public bool $showPackageStep   = false;
    public bool $showDateStep      = false;
    public bool $showLocationStep  = false;
    public bool $showContactStep   = false;
    public bool $showSummaryStep   = false;

    // ── Package ──────────────────────────────────────────────────
    public ?int  $selectedPackageId = null;
    public bool  $isCustomPackage   = false;
    public array $selectedOptions   = [];

    // ── Date / Time ──────────────────────────────────────────────
    public string  $eventDate  = '';
    public ?string $dateStatus = null;
    public string  $startTime  = '';
    public string  $endTime    = '';

    // Appointment
    public string $slotStart       = '';
    public string $slotEnd         = '';
    public int    $durationMinutes = 60;

    // Calendar
    public int $calMonth;
    public int $calYear;

    // ── Location ─────────────────────────────────────────────────
    public ?int   $venueId     = null;
    public string $venueCustom = '';
    public ?int   $wilayaId    = null;

    // ── Contact ──────────────────────────────────────────────────
    public string  $name      = '';
    public string  $email     = '';
    public string  $phone     = '';
    public string  $promoCode = '';
    public ?array  $promoResult = null;

    // ── Pricing ──────────────────────────────────────────────────
    public array $pricing = [
        'base'         => 0,
        'options_cost' => 0,
        'time_cost'    => 0,
        'travel_cost'  => 0,
        'subtotal'     => 0,
        'total'        => 0,
        'deposit'      => 0,
    ];

    // ── Result ───────────────────────────────────────────────────
    public bool    $bookingComplete   = false;
    public ?int    $bookingId         = null;
    public ?string $generatedPassword = null;
    public bool    $existingAccount   = false;

    // ── Accordion state (persists across re-renders) ─────────────
    public array $openSections = [
        'category' => true,
        'service'  => true,
        'package'  => true,
        'date'     => true,
        'location' => true,
        'contact'  => true,
    ];

    public function toggleSection(string $key): void
    {
        $this->openSections[$key] = ! ($this->openSections[$key] ?? true);
    }

    // ── Cache ────────────────────────────────────────────────────
    private ?Service $serviceCache = null;

    // ────────────────────────────────────────────────────────────
    // Mount
    // ────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->calMonth = (int) date('n');
        $this->calYear  = (int) date('Y');

        // إعادة بناء الحالة من URL params
        if ($this->selectedCategoryId) {
            $this->showServiceStep = true;
        }
        if ($this->selectedServiceId) {
            $this->showServiceStep = true;
            $this->showPackageStep = true;
            $this->_initService();
        }
    }

    // ────────────────────────────────────────────────────────────
    // Getters
    // ────────────────────────────────────────────────────────────

    private function getService(): ?Service
    {
        if (! $this->selectedServiceId) return null;

        if (! $this->serviceCache || $this->serviceCache->id !== $this->selectedServiceId) {
            $this->serviceCache = Service::with([
                'packages' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            ])->find($this->selectedServiceId);
        }

        return $this->serviceCache;
    }

    private function getBookingType(): string
    {
        $svc = $this->getService();
        if (! $svc) return 'event';

        return $svc->booking_type instanceof \App\Enums\BookingType
            ? $svc->booking_type->value
            : (string) $svc->booking_type;
    }

    private function isWeddingMode(): bool
    {
        $svc = $this->getService();
        if (! $svc) return false;

        $mode = $svc->time_mode;
        if ($mode instanceof \App\Enums\TimeMode) return $mode === \App\Enums\TimeMode::Wedding;
        return (string) $mode === 'wedding';
    }

    // ────────────────────────────────────────────────────────────
    // Step 0 → اختر التصنيف
    // ────────────────────────────────────────────────────────────

    public function selectCategory(int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->selectedServiceId  = null;
        $this->showServiceStep    = true;
        $this->showPackageStep    = false;
        $this->showDateStep       = false;
        $this->showLocationStep   = false;
        $this->showContactStep    = false;
        $this->showSummaryStep    = false;
        $this->_resetPackage();
        $this->resetErrorBag();

        $this->dispatch('scroll-to', target: 'service-step');
    }

    // ────────────────────────────────────────────────────────────
    // Step 1 → اختر الخدمة
    // ────────────────────────────────────────────────────────────

    public function selectService(int $serviceId): void
    {
        $this->selectedServiceId = $serviceId;
        $this->serviceCache      = null;
        $this->showPackageStep   = true;
        $this->showDateStep      = false;
        $this->showLocationStep  = false;
        $this->showContactStep   = false;
        $this->showSummaryStep   = false;
        $this->_resetPackage();
        $this->resetErrorBag();

        $this->_initService();
        $this->dispatch('scroll-to', target: 'package-step');
    }

    private function _initService(): void
    {
        $svc = $this->getService();
        if (! $svc) return;

        $this->pricing['deposit'] = (float) ($svc->deposit_amount ?? 10000);

        // Default wilaya
        $wilaya = Wilaya::where('is_local', true)->first();
        $this->wilayaId = $wilaya?->id;

        // Default times for wedding mode
        if ($this->isWeddingMode()) {
            $this->startTime = $svc->default_start_time
                ? Carbon::parse($svc->default_start_time)->format('H:i')
                : '19:00';
            $this->endTime = $svc->default_end_time
                ? Carbon::parse($svc->default_end_time)->format('H:i')
                : '04:00';
        }

        $this->recalculate();
    }

    // ────────────────────────────────────────────────────────────
    // Step 2 → اختر الباقة
    // ────────────────────────────────────────────────────────────

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
        $svc = $this->getService();
        $pkg = $svc?->packages->firstWhere('id', $packageId);

        $this->isCustomPackage = (bool) $pkg?->is_buildable;
        $this->selectedOptions = [];

        // For appointments: set duration
        if ($pkg?->duration && $this->getBookingType() === 'appointment') {
            $this->durationMinutes = (int) $pkg->duration;
        }

        $this->recalculate();
        $this->_openDateOrContact();
        $this->resetErrorBag('package');
    }

    public function enableCustomPackage(): void
    {
        $this->isCustomPackage   = true;
        $buildable = $this->getService()?->packages->firstWhere('is_buildable', true);
        $this->selectedPackageId = $buildable?->id;
        $this->selectedOptions   = [];
        $this->recalculate();
    }

    public function toggleOption(int $optionId): void
    {
        if (isset($this->selectedOptions[$optionId])) {
            unset($this->selectedOptions[$optionId]);
        } else {
            $this->selectedOptions[$optionId] = 1;
        }
        $this->recalculate();
    }

    public function confirmPackage(): void
    {
        if (! $this->selectedPackageId && ! $this->isCustomPackage) {
            $this->addError('package', 'يرجى اختيار باقة أولاً');
            return;
        }
        if ($this->isCustomPackage && empty($this->selectedOptions)) {
            $this->addError('package', 'اختر خياراً واحداً على الأقل');
            return;
        }

        $this->_openDateOrContact();
    }

    private function _openDateOrContact(): void
    {
        $type = $this->getBookingType();
        $svc  = $this->getService();

        if ($type === 'subscription') {
            // اشتراك: لا يحتاج تاريخ ولا موقع
            $this->showDateStep      = false;
            $this->showLocationStep  = false;
            $this->showContactStep   = true;
            $this->dispatch('scroll-to', target: 'contact-step');
        } else {
            $this->showDateStep = true;
            $this->dispatch('scroll-to', target: 'date-step');
        }
    }

    // ────────────────────────────────────────────────────────────
    // Step 3 → التاريخ / الوقت
    // ────────────────────────────────────────────────────────────

    public function selectDate(string $dateStr): void
    {
        if (Carbon::parse($dateStr)->startOfDay()->lt(Carbon::today())) return;
        $this->eventDate = $dateStr;
        $this->updatedEventDate($dateStr);
    }

    public function updatedEventDate(string $value): void
    {
        $this->dateStatus = null;
        if ($value === '') return;

        $this->dateStatus = app(AvailabilityService::class)
            ->getDateStatus($value, $this->selectedServiceId);

        $this->recalculate();
        $this->_openLocationOrContact();
    }

    public function updatedStartTime(): void
    {
        $this->recalculate();
        // Auto-calc slot end for appointments
        if ($this->getBookingType() === 'appointment' && $this->slotStart && $this->durationMinutes > 0) {
            $start = Carbon::createFromFormat('H:i', $this->slotStart);
            $this->slotEnd = $start->addMinutes($this->durationMinutes)->format('H:i');
        }
    }

    public function updatedEndTime(): void   { $this->recalculate(); }
    public function updatedSlotStart(): void { $this->updatedStartTime(); }

    public function confirmDate(): void
    {
        $type = $this->getBookingType();

        if ($type === 'appointment') {
            $this->validate([
                'eventDate' => 'required|date|after_or_equal:today',
                'slotStart' => 'required|date_format:H:i',
                'slotEnd'   => 'required|date_format:H:i',
            ]);

            $available = app(AvailabilityService::class)
                ->isSlotAvailable($this->eventDate, $this->slotStart, $this->slotEnd, $this->selectedServiceId);

            if (! $available) {
                $this->addError('slotStart', 'هذا الوقت محجوز، اختر وقتاً آخر');
                return;
            }
        } else {
            $this->validate([
                'eventDate' => 'required|date|after_or_equal:today',
                'startTime' => 'required|date_format:H:i',
                'endTime'   => 'required|date_format:H:i',
            ]);

            if ($this->dateStatus !== 'available') {
                $this->addError('eventDate', 'التاريخ غير متاح، اختر تاريخاً آخر');
                return;
            }
        }

        $this->_openLocationOrContact();
    }

    private function _openLocationOrContact(): void
    {
        $svc = $this->getService();

        if ($svc && ($svc->show_wilaya_selector || $svc->show_venue_selector)) {
            $this->showLocationStep = true;
            $this->dispatch('scroll-to', target: 'location-step');
        } else {
            $this->showContactStep = true;
            $this->dispatch('scroll-to', target: 'contact-step');
        }
    }

    public function nextMonth(): void
    {
        if ($this->calMonth === 12) { $this->calMonth = 1; $this->calYear++; }
        else { $this->calMonth++; }
    }

    public function prevMonth(): void
    {
        if ($this->calMonth === 1) { $this->calMonth = 12; $this->calYear--; }
        else { $this->calMonth--; }
    }

    // ────────────────────────────────────────────────────────────
    // Step 4 → الموقع
    // ────────────────────────────────────────────────────────────

    public function updatedVenueId(): void   { $this->recalculate(); }

    public function updatedWilayaId(): void
    {
        $this->venueId = null;
        $this->recalculate();
    }

    public function confirmLocation(): void
    {
        $svc = $this->getService();

        if ($svc?->show_wilaya_selector) {
            $this->validate(['wilayaId' => 'required|exists:wilayas,id']);
        }

        if ($svc?->show_venue_selector && ! $this->venueId && trim($this->venueCustom) === '') {
            $this->addError('venue', 'اختر قاعة أو اكتب اسمها');
            return;
        }

        $this->showContactStep = true;
        $this->dispatch('scroll-to', target: 'contact-step');
    }

    // ────────────────────────────────────────────────────────────
    // Step 5 → بيانات التواصل
    // ────────────────────────────────────────────────────────────

    public function updatedName(): void    { $this->_maybeShowSummary(); }
    public function updatedEmail(): void   { $this->_maybeShowSummary(); }
    public function updatedPhone(): void   { $this->_maybeShowSummary(); }

    private function _maybeShowSummary(): void
    {
        if ($this->name && $this->email && $this->phone) {
            $this->showSummaryStep = true;
        }
    }

    public function checkPromo(): void
    {
        if (trim($this->promoCode) === '') {
            $this->promoResult = null;
            return;
        }
        $this->promoResult = app(BookingService::class)
            ->checkPromoCode($this->promoCode, $this->pricing['total']);
    }

    // ────────────────────────────────────────────────────────────
    // Submit
    // ────────────────────────────────────────────────────────────

    public function submitBooking(): void
    {
        // Rate limiting
        $key = 'booking-submit:' . (request()->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('booking', 'طلبات كثيرة، حاول بعد دقائق');
            return;
        }
        RateLimiter::hit($key, 300);

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
        ]);

        $svc  = $this->getService();
        if (! $svc) {
            $this->addError('booking', 'الخدمة غير متاحة');
            return;
        }

        $package = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;
        $type    = $this->getBookingType();

        try {
            $this->recalculate();
            $calculator = app(PricingCalculator::class);

            $result = match ($type) {
                'appointment' => $this->_submitAppointment($svc, $package, $calculator),
                'subscription' => $this->_submitSubscription($svc, $package, $calculator),
                default => $this->_submitEvent($svc, $package, $calculator),
            };

            $this->bookingId         = $result['booking']->id;
            $this->generatedPassword = $result['generated_password'] ?? null;
            $this->existingAccount   = $result['generated_password'] === null;
            $this->bookingComplete   = true;

        } catch (\Exception $e) {
            $this->addError('booking', $e->getMessage());
        }
    }

    private function _submitEvent(Service $svc, ?Package $pkg, PricingCalculator $calc): array
    {
        $pricing = $calc->calculateEvent(
            $svc, $pkg,
            $this->isCustomPackage ? $this->selectedOptions : [],
            $this->startTime ?: null, $this->endTime ?: null,
            $this->venueId, $this->wilayaId,
        );

        return app(BookingService::class)->createEventBooking([
            'name'             => $this->name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'service_id'       => $this->selectedServiceId,
            'event_date'       => $this->eventDate,
            'start_time'       => $this->startTime,
            'end_time'         => $this->endTime,
            'venue_id'         => $this->venueId,
            'venue_custom'     => $this->venueCustom,
            'wilaya_id'        => $this->wilayaId,
            'promo_code'       => trim($this->promoCode) ?: null,
            'package_name'     => $pkg?->name ?? ($this->isCustomPackage ? 'مخصصة' : null),
            'package_id'       => $this->selectedPackageId,
            'selected_options' => $this->isCustomPackage ? $this->selectedOptions : [],
            'package_snapshot' => $pkg ? ['name' => $pkg->name, 'price' => (float) $pkg->price] : null,
        ], $pricing);
    }

    private function _submitAppointment(Service $svc, ?Package $pkg, PricingCalculator $calc): array
    {
        $pricing = $calc->calculateAppointment($svc, $pkg);

        return app(BookingService::class)->createAppointmentBooking([
            'name'             => $this->name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'service_id'       => $this->selectedServiceId,
            'offer_id'         => null,
            'package_id'       => $this->selectedPackageId,
            'appointment_date' => $this->eventDate,
            'slot_start'       => $this->slotStart,
            'slot_end'         => $this->slotEnd,
            'duration_minutes' => $this->durationMinutes,
            'promo_code'       => trim($this->promoCode) ?: null,
            'package_name'     => $pkg?->name,
            'package_snapshot' => $pkg ? ['name' => $pkg->name, 'price' => (float) $pkg->price] : null,
        ], $pricing);
    }

    private function _submitSubscription(Service $svc, ?Package $pkg, PricingCalculator $calc): array
    {
        $pricing = $calc->calculateSubscription($pkg);

        return app(BookingService::class)->createSubscriptionBooking([
            'name'             => $this->name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'service_id'       => $this->selectedServiceId,
            'offer_id'         => null,
            'package_id'       => $this->selectedPackageId,
            'billing_cycle'    => 'monthly',
            'promo_code'       => trim($this->promoCode) ?: null,
            'package_name'     => $pkg?->name,
            'package_snapshot' => $pkg ? ['name' => $pkg->name, 'price' => (float) $pkg->price] : null,
        ], $pricing);
    }

    // ────────────────────────────────────────────────────────────
    // Pricing
    // ────────────────────────────────────────────────────────────

    private function recalculate(): void
    {
        $svc  = $this->getService();
        if (! $svc) return;

        $pkg  = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;
        $type = $this->getBookingType();
        $calc = app(PricingCalculator::class);

        $result = match ($type) {
            'appointment'  => $calc->calculateAppointment($svc, $pkg),
            'subscription' => $calc->calculateSubscription($pkg),
            default        => $calc->calculateEvent(
                $svc, $pkg,
                $this->isCustomPackage ? $this->selectedOptions : [],
                $this->startTime ?: null, $this->endTime ?: null,
                $this->venueId, $this->wilayaId,
            ),
        };

        $this->pricing = $result->toArray();
    }

    // ────────────────────────────────────────────────────────────
    // Reset helpers
    // ────────────────────────────────────────────────────────────

    private function _resetPackage(): void
    {
        $this->selectedPackageId = null;
        $this->isCustomPackage   = false;
        $this->selectedOptions   = [];
        $this->eventDate         = '';
        $this->dateStatus        = null;
        $this->startTime         = '';
        $this->endTime           = '';
        $this->slotStart         = '';
        $this->slotEnd           = '';
        $this->venueId           = null;
        $this->venueCustom       = '';
        $this->promoCode         = '';
        $this->promoResult       = null;
        $this->pricing           = array_fill_keys(array_keys($this->pricing), 0);
    }

    // ────────────────────────────────────────────────────────────
    // Render
    // ────────────────────────────────────────────────────────────

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $services = $this->selectedCategoryId
            ? Service::where('is_active', true)->where('category_id', $this->selectedCategoryId)->orderBy('sort_order')->get()
            : collect();

        $svc  = $this->getService();
        $type = $this->getBookingType();

        $packages = $svc
            ? $svc->packages->where('is_active', true)
            : collect();

        $packageOptions = ($this->isCustomPackage && $this->selectedPackageId)
            ? PackageOption::where('package_id', $this->selectedPackageId)->where('is_active', true)->get()
            : collect();

        $venuesQuery = Venue::where('is_active', true)->orderBy('sort_order')->orderBy('name');
        if ($this->wilayaId) $venuesQuery->where('wilaya_id', $this->wilayaId);
        $venues  = $venuesQuery->get();
        $wilayas = Wilaya::orderBy('code')->get();

        // محتوى ثقة — يظهر في المساحات الفاضية
        $recentWorks  = PortfolioItem::where('is_active', true)->where('is_featured', true)->latest()->limit(3)->get();
        $testimonials = Testimonial::where('is_active', true)->where('status', 'approved')->inRandomOrder()->limit(2)->get();

        return view('livewire.booking.smart-booking-form', compact(
            'categories', 'services', 'svc', 'type',
            'packages', 'packageOptions',
            'venues', 'wilayas',
            'recentWorks', 'testimonials',
        ));
    }
}