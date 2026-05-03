<?php

namespace App\Livewire\Booking;

use App\DTOs\PricingResult;
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

class EventBookingForm extends Component
{
    public int $serviceId;
    public Service $service;
    public int $currentStep = 1;
    public ?int $selectedPackageId = null;
    public bool $isCustomPackage = false;
    public array $selectedOptions = [];
    public string $eventDate = '';
    public ?bool $dateAvailable = null;
    public string $startTime = '';
    public string $endTime = '';
    public ?int $venueId = null;
    public string $venueCustom = '';
    public ?int $wilayaId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public bool $bookingComplete = false;
    public ?int $bookingId = null;
    public string $bookingReference = '';
    public ?string $generatedPassword = null;
    public bool $existingAccount = false;
    public bool $checkingDate = false;

    public array $pricingSummary = [
        'base'         => 0,
        'options_cost' => 0,
        'time_cost'    => 0,
        'travel_cost'  => 0,
        'subtotal'     => 0,
        'total'        => 0,
        'deposit'      => 10000,
    ];

    public function mount(int $serviceId): void
    {
        $this->serviceId = $serviceId;
        $this->service   = Service::with([
            'packages' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ])->findOrFail($serviceId);

        $this->pricingSummary['deposit'] = (float) ($this->service->deposit_amount ?? 10000);

        $wilaya = Wilaya::where('is_local', true)->first();
        $this->wilayaId = $wilaya?->id;

        if ($this->isWeddingMode()) {
            $this->startTime = $this->service->default_start_time
                ? Carbon::parse($this->service->default_start_time)->format('H:i')
                : '19:00';
            $this->endTime = $this->service->default_end_time
                ? Carbon::parse($this->service->default_end_time)->format('H:i')
                : '04:00';
        }

        $this->recalculate();
    }

    private function isWeddingMode(): bool
    {
        $mode = $this->service->time_mode;
        if ($mode instanceof TimeMode) {
            return $mode === TimeMode::Wedding;
        }
        return (string) $mode === 'wedding';
    }

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
        $this->isCustomPackage   = false;
        $this->selectedOptions   = [];
        $this->recalculate();
    }

    public function enableCustomPackage(): void
    {
        $this->isCustomPackage   = true;
        $this->selectedPackageId = null;
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

    public function updatedEventDate(string $value): void
    {
        $this->checkingDate  = true;
        $this->dateAvailable = null;

        if ($value === '') {
            $this->checkingDate = false;
            return;
        }

        $this->dateAvailable = app(AvailabilityService::class)->isDateAvailable($value);
        $this->checkingDate  = false;
        $this->recalculate();
    }

    public function updatedStartTime(): void { $this->recalculate(); }
    public function updatedEndTime(): void   { $this->recalculate(); }
    public function updatedVenueId(): void   { $this->recalculate(); }
    public function updatedWilayaId(): void  { $this->recalculate(); }

    private function recalculate(): void
    {
        $package = $this->selectedPackageId
            ? Package::find($this->selectedPackageId)
            : null;

        $opts = $this->isCustomPackage ? $this->selectedOptions : [];

        $result = app(PricingCalculator::class)->calculateEvent(
            $this->service,
            $package,
            $opts,
            $this->startTime ?: null,
            $this->endTime ?: null,
            $this->venueId,
            $this->wilayaId,
        );

        $this->pricingSummary = $result->toArray();
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            if (!$this->selectedPackageId && !$this->isCustomPackage) {
                $this->addError('package', 'يرجى اختيار باقة أو بناء باقتك الخاصة');
                return;
            }
            if ($this->isCustomPackage && empty($this->selectedOptions)) {
                $this->addError('package', 'يرجى اختيار خيار واحد على الأقل');
                return;
            }
            $this->currentStep = 2;
            return;
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'eventDate' => 'required|date|after_or_equal:today',
                'startTime' => 'required|date_format:H:i',
                'endTime'   => 'required|date_format:H:i',
            ]);
            if ($this->dateAvailable !== true) {
                $this->addError('eventDate', 'التاريخ غير متاح أو لم يتم التحقق منه.');
                return;
            }
            $this->currentStep = ($this->service->show_venue_selector || $this->service->show_wilaya_selector) ? 3 : 4;
            return;
        }

        if ($this->currentStep === 3) {
            $this->validate(['wilayaId' => 'required|exists:wilayas,id']);
            if ($this->service->show_venue_selector && !$this->venueId && trim($this->venueCustom) === '') {
                $this->addError('venue', 'يرجى اختيار قاعة أو كتابة اسمها');
                return;
            }
            $this->currentStep = 4;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep <= 1) {
            return;
        }

        if ($this->currentStep === 4 && !$this->service->show_venue_selector && !$this->service->show_wilaya_selector) {
            $this->currentStep = 2;
            return;
        }
        $this->currentStep--;
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

        try {
            $package = $this->selectedPackageId
                ? Package::find($this->selectedPackageId)
                : null;

            $pricingResult = app(PricingCalculator::class)->calculateEvent(
                $this->service,
                $package,
                $this->isCustomPackage ? $this->selectedOptions : [],
                $this->startTime ?: null,
                $this->endTime ?: null,
                $this->venueId,
                $this->wilayaId,
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
                'package_id'       => $this->selectedPackageId,
                'package_name'     => $package?->name ?? ($this->isCustomPackage ? 'مخصصة' : null),
                'package_snapshot' => $package ? [
                    'name'  => $package->name,
                    'price' => (float) $package->price,
                ] : null,
                'selected_options' => $this->isCustomPackage ? $this->selectedOptions : [],
            ], $pricingResult);

            $this->bookingId        = $result['booking']->id;
            $this->bookingReference = str_pad($result['booking']->id, 4, '0', STR_PAD_LEFT);
            $this->generatedPassword = $result['generated_password'] ?? null;
            $this->existingAccount  = $result['generated_password'] === null;
            $this->bookingComplete  = true;
            $this->currentStep      = 5;
        } catch (\Exception $e) {
            $this->addError('booking', $e->getMessage());
        }
    }

    public function render()
    {
        $options = PackageOption::whereHas('package', fn($q) => $q->where('service_id', $this->serviceId))->where('is_active', true)->get();
        $venues  = Venue::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $wilayas = Wilaya::orderBy('code')->get();

        return view('livewire.event-booking-form', compact('options', 'venues', 'wilayas'));
    }
}
