<?php

namespace Tests\Feature;

use App\DTOs\PricingResult;
use App\Models\Promo\PromoCode;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\Service;
use App\Services\BookingService;
use App\Services\PromoCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;

    private PromoCodeService $promoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->promoService = app(PromoCodeService::class);
    }

    /** @test */
    public function valid_percent_promo_applies_discount(): void
    {
        PromoCode::create([
            'code'          => 'SAVE20',
            'discount_type' => 'percent',
            'value'         => 20,
            'is_active'     => true,
        ]);

        $result = $this->promoService->validateAndApply('SAVE20', 50000);

        $this->assertTrue($result['valid']);
        $this->assertEquals(10000, $result['discount_amount']);
        $this->assertEquals(40000, $result['final_price']);
    }

    /** @test */
    public function valid_fixed_promo_applies_discount(): void
    {
        PromoCode::create([
            'code'          => 'FLAT5K',
            'discount_type' => 'fixed',
            'value'         => 5000,
            'is_active'     => true,
        ]);

        $result = $this->promoService->validateAndApply('FLAT5K', 30000);

        $this->assertTrue($result['valid']);
        $this->assertEquals(5000, $result['discount_amount']);
        $this->assertEquals(25000, $result['final_price']);
    }

    /** @test */
    public function invalid_code_returns_error(): void
    {
        $result = $this->promoService->validateAndApply('DOESNOTEXIST', 50000);

        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function inactive_code_returns_error(): void
    {
        PromoCode::create([
            'code'          => 'EXPIRED',
            'discount_type' => 'percent',
            'value'         => 10,
            'is_active'     => false,
        ]);

        $result = $this->promoService->validateAndApply('EXPIRED', 50000);

        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function exhausted_code_returns_error(): void
    {
        PromoCode::create([
            'code'          => 'LIMITED',
            'discount_type' => 'percent',
            'value'         => 10,
            'is_active'     => true,
            'max_uses'      => 5,
            'used_count'    => 5,
        ]);

        $result = $this->promoService->validateAndApply('LIMITED', 50000);

        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function min_order_not_met_returns_error(): void
    {
        PromoCode::create([
            'code'            => 'MINORDER',
            'discount_type'   => 'percent',
            'value'           => 10,
            'is_active'       => true,
            'min_order_value' => 100000,
        ]);

        $result = $this->promoService->validateAndApply('MINORDER', 50000);

        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function increment_used_increases_count_atomically(): void
    {
        $promo = PromoCode::create([
            'code'          => 'COUNTER',
            'discount_type' => 'percent',
            'value'         => 10,
            'is_active'     => true,
            'max_uses'      => 10,
            'used_count'    => 0,
        ]);

        $this->promoService->incrementUsed($promo->id);
        $this->promoService->incrementUsed($promo->id);

        $this->assertEquals(2, $promo->fresh()->used_count);
    }

    /** @test */
    public function promo_applied_on_event_booking(): void
    {
        $category = Category::create([
            'name' => 'تصنيف', 'slug' => 'cat-promo', 'is_active' => true,
        ]);
        $service = Service::create([
            'category_id' => $category->id, 'name' => 'خدمة',
            'slug' => 'svc-promo', 'booking_type' => 'event',
            'is_active' => true,
        ]);

        PromoCode::create([
            'code' => 'EVENT10', 'discount_type' => 'percent',
            'value' => 10, 'is_active' => true,
        ]);

        $pricing = new PricingResult(
            base: 50000, optionsCost: 0, timeCost: 0,
            travelCost: 0, subtotal: 50000, total: 50000, deposit: 0,
        );

        $result = app(BookingService::class)->createEventBooking([
            'name'       => 'عميل',
            'phone'      => '0559998888',
            'service_id' => $service->id,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'promo_code' => 'EVENT10',
        ], $pricing);

        $booking = $result['booking'];
        $this->assertEquals(5000, (float) $booking->discount_amount);
        $this->assertEquals(45000, (float) $booking->final_price);
    }
}
