<?php

namespace App\Livewire\Booking;

use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Livewire\Component;

class AppointmentBookingWizard extends Component
{
    public int $serviceId;
    public int $currentStep = 1;

    // Step 1: Package selection
    public ?int $selectedPackageId = null;

    // Step 2: Slot
    public string $appointmentDate = '';
    public ?string $dateStatus = null;
    public string $slotStart = '';
    public string $slotEnd = '';
    public int $durationMinutes = 60;

    // Step 3: Contact
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $promoCode = '';
    public ?array $promoResult = null;

    // Pricing
    public array $pricing = [
        'base' => 0, 'options_cost' => 0, 'time_cost' => 0,
        'travel_cost' => 0, 'subtotal' => 0, 'total' => 0, 'deposit' => 0,
    ];

    public function mount(int $serviceId): void
    {
        $this->serviceId = $serviceId;
        $this->recalculate();
    }

    private function getService(): Service
    {
        return Service::with(['packages' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])->findOrFail($this->serviceId);
    }

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;

        $pkg = Package::find($packageId);
        if ($pkg?->duration) {
            $this->durationMinutes = (int) $pkg->duration;
        }

        $this->recalculate();
    }

    public function updatedAppointmentDate(string $value): void
    {
        $this->dateStatus = null;
        if ($value === '') return;
        $this->dateStatus = app(AvailabilityService::class)
            ->getDateStatus($value, $this->serviceId);
    }

    public function updatedSlotStart(string $value): void
    {
        if ($value && $this->durationMinutes > 0) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $value);
            $this->slotEnd = $start->addMinutes($this->durationMinutes)->format('H:i');
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

    private function recalculate(): void
    {
        $service = $this->getService();
        $package = $this->selectedPackageId
            ? Package::find($this->selectedPackageId)
            : null;

        $result = app(PricingCalculator::class)->calculateAppointment($service, $package);
        $this->pricing = $result->toArray();
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            if (!$this->selectedPackageId) {
                $this->addError('package', 'يرجى اختيار باقة');
                return;
            }
            $this->currentStep = 2;
            return;
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'appointmentDate' => 'required|date|after_or_equal:today',
                'slotStart'       => 'required|date_format:H:i',
                'slotEnd'         => 'required|date_format:H:i',
            ]);

            if ($this->dateStatus !== 'available') {
                $this->addError('appointmentDate', 'التاريخ غير متاح.');
                return;
            }

            $available = app(AvailabilityService::class)->isSlotAvailable(
                $this->appointmentDate, $this->slotStart, $this->slotEnd, $this->serviceId
            );
            if (!$available) {
                $this->addError('slotStart', 'هذا الوقت محجوز. اختر وقتًا آخر.');
                return;
            }

            $this->currentStep = 3;
            return;
        }

        if ($this->currentStep === 3) {
            $this->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:30',
            ]);
            $this->recalculate();
            $this->currentStep = 4;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep <= 1) {
            $this->dispatch('booking-go-back');
            return;
        }
        if ($this->currentStep === 4) {
            $this->currentStep = 3;
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
            $service = $this->getService();
            $package = $this->selectedPackageId
                ? Package::find($this->selectedPackageId)
                : null;

            $pricing = app(PricingCalculator::class)->calculateAppointment($service, $package);

            $result = app(BookingService::class)->createAppointmentBooking([
                'name'             => $this->name,
                'email'            => $this->email,
                'phone'            => $this->phone,
                'service_id'       => $this->serviceId,
                'offer_id'         => null,
                'package_id'       => $this->selectedPackageId,
                'appointment_date' => $this->appointmentDate,
                'slot_start'       => $this->slotStart,
                'slot_end'         => $this->slotEnd,
                'duration_minutes' => $this->durationMinutes,
                'promo_code'       => trim($this->promoCode) ?: null,
                'package_name'     => $package?->name,
                'package_snapshot' => $package ? [
                    'name'  => $package->name,
                    'price' => (float) $package->price,
                ] : null,
            ], $pricing);

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

        return view('livewire.booking.appointment-booking-wizard', [
            'service' => $service,
        ]);
    }
}
