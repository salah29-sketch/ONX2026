<?php

namespace App\Livewire\Booking;

use App\Models\Service\Category;
use App\Models\Service\Service;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class BookingPage extends Component
{
    #[Url(as: 'type', except: '')]
    public ?string $selectedType = null;

    #[Url(as: 'category', except: '')]
    public ?int $selectedCategoryId = null;

    #[Url(as: 'service', except: '')]
    public ?int $selectedServiceId = null;

    public bool    $bookingComplete   = false;
    public ?int    $bookingId         = null;
    public ?string $generatedPassword = null;
    public bool    $existingAccount   = false;

    // ── Actions ──────────────────────────────────────────────────

    public function selectCategory(int $categoryId, string $type): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->selectedType       = $type;
        $this->selectedServiceId  = null;
        $this->resetErrorBag();
    }

    public function selectService(int $serviceId): void
    {
        $this->selectedServiceId = $serviceId;

        $service = Service::find($serviceId);
        if ($service) {
            $this->selectedType = $service->booking_type instanceof \App\Enums\BookingType
                ? $service->booking_type->value
                : $service->booking_type;
        }

        $this->resetErrorBag();
    }

    public function goBack(): void
    {
        if ($this->selectedServiceId) {
            $this->selectedServiceId = null;
        } elseif ($this->selectedCategoryId) {
            $this->selectedCategoryId = null;
            $this->selectedType       = null;
        }
    }

    // ── Listeners ────────────────────────────────────────────────

    #[On('booking-completed')]
    public function onBookingCompleted(int $bookingId, ?string $password, bool $existing): void
    {
        $this->bookingId         = $bookingId;
        $this->generatedPassword = $password;
        $this->existingAccount   = $existing;
        $this->bookingComplete   = true;
    }

    #[On('booking-go-back')]
    public function onGoBack(): void
    {
        $this->goBack();
    }

    // ── Render ───────────────────────────────────────────────────

    public function render()
    {
        if ($this->selectedServiceId) {
            $service = Service::find($this->selectedServiceId);
            if ($service) {
                $this->selectedType = $service->booking_type instanceof \App\Enums\BookingType
                    ? $service->booking_type->value
                    : $service->booking_type;
            } else {
                $this->selectedServiceId = null;
            }
        }

        if (! $this->selectedCategoryId) {
            $categories = Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return view('livewire.booking.booking-page', [
                'categories' => $categories,
                'services'   => collect(),
            ]);
        }

        if (! $this->selectedServiceId) {
            $services = Service::where('is_active', true)
                ->where('category_id', $this->selectedCategoryId)
                ->orderBy('sort_order')
                ->get();

            return view('livewire.booking.booking-page', [
                'categories' => collect(),
                'services'   => $services,
            ]);
        }

        return view('livewire.booking.booking-page', [
            'categories' => collect(),
            'services'   => collect(),
        ]);
    }
}
