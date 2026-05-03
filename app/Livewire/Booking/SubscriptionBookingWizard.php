<?php

namespace App\Livewire\Booking;

use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\BookingService;
use App\Services\PricingCalculator;
use Livewire\Component;

class SubscriptionBookingWizard extends Component
{
    public int $serviceId;
    public int $currentStep = 1;

    public ?int $selectedPackageId = null;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $promoCode = '';
    public ?array $promoResult = null;

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

    public function selectPlan(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
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
        $package = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;
        $result = app(PricingCalculator::class)->calculateSubscription($package);
        $this->pricing = $result->toArray();
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            if (!$this->selectedPackageId) {
                $this->addError('plan', 'يرجى اختيار خطة اشتراك');
                return;
            }
            $this->currentStep = 2;
            return;
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:30',
            ]);
            $this->recalculate();
            $this->currentStep = 3;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep <= 1) {
            $this->dispatch('booking-go-back');
            return;
        }
        if ($this->currentStep === 3) {
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
            $package = $this->selectedPackageId ? Package::find($this->selectedPackageId) : null;
            $pricing = app(PricingCalculator::class)->calculateSubscription($package);

            $result = app(BookingService::class)->createSubscriptionBooking([
                'name'             => $this->name,
                'email'            => $this->email,
                'phone'            => $this->phone,
                'service_id'       => $this->serviceId,
                'offer_id'         => null,
                'package_id'       => $this->selectedPackageId,
                'billing_cycle'    => 'monthly',
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
        return view('livewire.booking.subscription-booking-wizard', [
            'service' => $this->getService(),
        ]);
    }
}
