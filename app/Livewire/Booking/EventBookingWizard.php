<?php

namespace App\Livewire\Booking;

use App\Enums\TimeMode;
use App\Models\Event\Venue;
use App\Models\Event\Wilaya;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Carbon\Carbon;
use Livewire\Component;

class EventBookingWizard extends Component
{
    public int $serviceId;
    public int $currentStep = 1;
    public bool $showPackageSection = true;

    // Step 1
    public ?int  $selectedPackageId = null;
    public bool  $isCustomPackage   = false;
    public array $selectedOptions   = [];

    // Step 2
    public string  $eventDate  = '';
    public ?string $dateStatus = null;
    public string  $startTime  = '';
    public string  $endTime    = '';
    public int     $calMonth;
    public int     $calYear;

    // Step 3
    public ?int   $venueId     = null;
    public string $venueCustom = '';
    public ?int   $wilayaId    = null;

    // Step 4
    public string  $name      = '';
    public string  $email     = '';
    public string  $phone     = '';
    public string  $promoCode = '';
    public ?array  $promoResult = null;

    // Pricing
    public array $pricing = [
        'base'         => 0,
        'options_cost' => 0,
        'time_cost'    => 0,
        'travel_cost'  => 0,
        'subtotal'     => 0,
        'total'        => 0,
        'deposit'      => 0,
    ];

    private ?Service $serviceCache = null;

    public function mount(int $serviceId): void
    {
        $this->serviceId = $serviceId;
        $service = $this->getService();

        $this->pricing['deposit'] = (float) ($service->deposit_amount ?? 10000);

        $wilaya = Wilaya::where('is_local', true)->first();
        $this->wilayaId = $wilaya?->id;

        if ($this->isWeddingMode()) {
            $this->startTime = $service->default_start_time
                ? Carbon::parse($service->default_start_time)->format('H:i')
                : '19:00';
            $this->endTime = $service->default_end_time
                ? Carbon::parse($service->default_end_time)->format('H:i')
                : '04:00';
        }

        $this->calMonth = (int) date('n');
        $this->calYear  = (int) date('Y');

        $this->recalculate();
    }

    private function getService(): Service
    {
        if (! $this->serviceCache || $this->serviceCache->id !== $this->serviceId) {
            $this->serviceCache = Service::with([
                'packages' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            ])->findOrFail($this->serviceId);
        }
        return $this->serviceCache;
    }

    private function isWeddingMode(): bool
    {
        $mode = $this->getService()->time_mode;
        if ($mode instanceof TimeMode) return $mode === TimeMode::Wedding;
        return (string) $mode === 'wedding';
    }

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
        $pkg = $this->getService()->packages->firstWhere('id', $packageId);
        $this->isCustomPackage   = (bool) $pkg?->is_buildable;
        $this->selectedOptions   = [];
        $this->recalculate();
    }

    public function enableCustomPackage(): void
    {
        $this->isCustomPackage   = true;
        $buildablePkg = $this->getService()->packages->firstWhere('is_buildable', true);
        $this->selectedPackageId = $buildablePkg?->id;
        $this->selectedOptions   = [];
        $this->recalculate();
    }

    public function togglePackageSection(): void
    {
        $this->showPackageSection = !$this->showPackageSection;
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

    public function updatedEventDate(string $value): void
    {
        $this->dateStatus = null;
        if ($value === '') return;
        $this->dateStatus = app(AvailabilityService::class)
            ->getDateStatus($value, $this->serviceId);
        $this->recalculate();
    }

    public function selectDate(string $dateStr): void
    {
        if (Carbon::parse($dateStr)->startOfDay()->lt(Carbon::today())) return;
        $this->eventDate = $dateStr;
        $this->updatedEventDate($dateStr);
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

    public function updatedStartTime(): void { $this->recalculate(); }
    public function updatedEndTime(): void   { $this->recalculate(); }
    public function updatedVenueId(): void   { $this->recalculate(); }

    public function updatedWilayaId(): void
    {
        $this->venueId = null;
        $this->recalculate();
    }

    public function checkPromo(): void
    {
        if (trim($this->promoCode) === '') { $this->promoResult = null; return; }
        $this->promoResult = app(BookingService::class)
            ->checkPromoCode($this->promoCode, $this->pricing['total']);
    }

    private function recalculate(): void
    {
        $service = $this->getService();
        $package = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;
        $opts = $this->isCustomPackage ? $this->selectedOptions : [];

        $result = app(PricingCalculator::class)->calculateEvent(
            $service, $package, $opts,
            $this->startTime ?: null, $this->endTime ?: null,
            $this->venueId, $this->wilayaId,
        );
        $this->pricing = $result->toArray();
    }

    public function nextStep(): void
    {
        $service = $this->getService();

        // Validate package selection
        if (! $this->selectedPackageId && ! $this->isCustomPackage) {
            $this->addError('package', 'يرجى اختيار باقة أو بناء باقتك الخاصة');
            return;
        }
        if ($this->isCustomPackage && empty($this->selectedOptions)) {
            $this->addError('package', 'يرجى اختيار خيار واحد على الأقل');
            return;
        }

        // Validate date & time
        $this->validate([
            'eventDate' => 'required|date|after_or_equal:today',
            'startTime' => 'required|date_format:H:i',
            'endTime'   => 'required|date_format:H:i',
        ]);
        if ($this->dateStatus !== 'available') {
            $this->addError('eventDate', 'التاريخ غير متاح. اختر تاريخاً آخر.');
            return;
        }

        // Validate contact data
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
        ];
        
        if ($service->show_wilaya_selector) {
            $rules['wilayaId'] = 'required|exists:wilayas,id';
        }

        $this->validate($rules);
        
        if ($service->show_venue_selector && ! $this->venueId && trim($this->venueCustom) === '') {
            $this->addError('venue', 'يرجى اختيار قاعة أو كتابة اسمها');
            return;
        }
        
        $this->recalculate();
    }

    public function submitBooking(): void
    {
        $key = 'booking-submit:' . (request()->ip() ?? 'unknown');
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('booking', 'طلبات كثيرة. حاول بعد دقائق.');
            return;
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 300);

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
        ]);

        $service = $this->getService();
        $package = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;

        try {
            $this->recalculate();

            $pricingResult = app(PricingCalculator::class)->calculateEvent(
                $service, $package,
                $this->isCustomPackage ? $this->selectedOptions : [],
                $this->startTime ?: null, $this->endTime ?: null,
                $this->venueId, $this->wilayaId,
            );

            $result = app(BookingService::class)->createEventBooking([
                'name'             => $this->name,
                'email'            => $this->email,
                'phone'            => $this->phone,
                'service_id'       => $this->serviceId,
                'event_date'       => $this->eventDate,
                'start_time'       => $this->startTime,
                'end_time'         => $this->endTime,
                'venue_id'         => $this->venueId,
                'venue_custom'     => $this->venueCustom,
                'wilaya_id'        => $this->wilayaId,
                'promo_code'       => trim($this->promoCode) ?: null,
                'package_name'     => $package?->name ?? ($this->isCustomPackage ? 'مخصصة' : null),
                'package_id'       => $this->selectedPackageId,
                'selected_options' => $this->isCustomPackage ? $this->selectedOptions : [],
                'package_snapshot' => $package ? [
                    'name'  => $package->name,
                    'price' => (float) $package->price,
                ] : null,
            ], $pricingResult);

            $this->dispatch('booking-completed',
                bookingId: $result['booking']->id,
                password: $result['generated_password'],
                existing: $result['generated_password'] === null,
            );
        } catch (\Exception $e) {
            $this->addError('booking', $e->getMessage());
        }
    }

    public function render()
    {
        $service = $this->getService();

        $options = $this->isCustomPackage && $this->selectedPackageId
            ? PackageOption::where('package_id', $this->selectedPackageId)->where('is_active', true)->get()
            : collect([]);

        $venuesQuery = Venue::where('is_active', true)->orderBy('sort_order')->orderBy('name');
        if ($this->wilayaId) {
            $venuesQuery->where('wilaya_id', $this->wilayaId);
        }
        $venues = $venuesQuery->get();

        $wilayas = Wilaya::orderBy('code')->get();

        return view('livewire.booking.event-booking-wizard', [
            'service' => $service,
            'options' => $options,
            'venues'  => $venues,
            'wilayas' => $wilayas,
        ]);
    }
}
