<?php

namespace Tests\Feature;

use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;
use App\Services\PricingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageSystemTest extends TestCase
{
    use RefreshDatabase;

    private function seedServiceWithPackage(): array
    {
        $category = Category::create([
            'name'      => 'تصنيف اختبار',
            'slug'      => 'test-cat-' . uniqid(),
            'is_active' => true,
        ]);

        $service = Service::create([
            'category_id'  => $category->id,
            'name'         => 'خدمة اختبار',
            'slug'         => 'test-svc-' . uniqid(),
            'booking_type' => 'event',
            'pricing_mode' => 'package',
            'is_active'    => true,
        ]);

        $package = Package::create([
            'service_id' => $service->id,
            'name'       => 'باقة ذهبية',
            'price'      => 50000,
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        return [$category, $service, $package];
    }

    /** @test */
    public function package_belongs_to_service_and_category(): void
    {
        [$category, $service, $package] = $this->seedServiceWithPackage();

        $package->load('service.category');

        $this->assertTrue($package->service->is($service));
        $this->assertTrue($package->service->category->is($category));
    }

    /** @test */
    public function package_options_belong_to_package(): void
    {
        [, , $package] = $this->seedServiceWithPackage();

        $option = PackageOption::create([
            'package_id'   => $package->id,
            'label'        => 'فيديو إضافي',
            'type'         => 'boolean',
            'price_effect' => 'fixed',
            'price'        => 15000,
            'is_active'    => true,
        ]);

        $this->assertSame(1, $package->activeOptions()->count());
        $this->assertTrue($option->package->is($package));
    }

    /** @test */
    public function fixed_option_returns_flat_price(): void
    {
        [, , $package] = $this->seedServiceWithPackage();

        $option = PackageOption::create([
            'package_id'   => $package->id,
            'label'        => 'ألبوم',
            'type'         => 'boolean',
            'price_effect' => 'fixed',
            'price'        => 8000,
            'is_active'    => true,
        ]);

        $this->assertEquals(8000, $option->calculatePrice(1));
        $this->assertEquals(8000, $option->calculatePrice(5));
    }

    /** @test */
    public function per_unit_option_multiplies_by_quantity(): void
    {
        [, , $package] = $this->seedServiceWithPackage();

        $option = PackageOption::create([
            'package_id'   => $package->id,
            'label'        => 'صور إضافية',
            'type'         => 'number',
            'price_effect' => 'per_unit',
            'price'        => 500,
            'min'          => 1,
            'max'          => 100,
            'is_active'    => true,
        ]);

        $this->assertEquals(500, $option->calculatePrice(1));
        $this->assertEquals(5000, $option->calculatePrice(10));
    }

    /** @test */
    public function free_option_returns_zero(): void
    {
        [, , $package] = $this->seedServiceWithPackage();

        $option = PackageOption::create([
            'package_id'   => $package->id,
            'label'        => 'ميزة مجانية',
            'type'         => 'boolean',
            'price_effect' => 'free',
            'price'        => 0,
            'is_active'    => true,
        ]);

        $this->assertEquals(0, $option->calculatePrice(1));
    }

    /** @test */
    public function active_packages_scope_filters_correctly(): void
    {
        [, $service] = $this->seedServiceWithPackage();

        Package::create([
            'service_id' => $service->id,
            'name'       => 'باقة مخفية',
            'price'      => 30000,
            'is_active'  => false,
            'sort_order' => 2,
        ]);

        $this->assertSame(1, $service->activePackages()->count());
        $this->assertSame(2, $service->packages()->count());
    }

    /** @test */
    public function api_returns_active_packages_for_service(): void
    {
        [, $service] = $this->seedServiceWithPackage();

        Package::create([
            'service_id' => $service->id,
            'name'       => 'باقة مخفية',
            'price'      => 30000,
            'is_active'  => false,
            'sort_order' => 2,
        ]);

        $res = $this->getJson('/api/packages?service_id=' . $service->id);
        $res->assertOk();
        $res->assertJsonCount(1);
    }

    /** @test */
    public function pricing_calculator_computes_event_total(): void
    {
        [, $service, $package] = $this->seedServiceWithPackage();

        $option = PackageOption::create([
            'package_id'   => $package->id,
            'label'        => 'فيديو',
            'type'         => 'boolean',
            'price_effect' => 'fixed',
            'price'        => 10000,
            'is_active'    => true,
        ]);

        $calculator = app(PricingCalculator::class);
        $result = $calculator->calculateEvent(
            service: $service,
            package: $package,
            selectedOptions: [$option->id => 1],
            startTime: null,
            endTime: null,
            venueId: null,
            wilayaId: null,
        );

        $this->assertEquals(50000, $result->base);
        $this->assertEquals(10000, $result->optionsCost);
        $this->assertEquals(60000, $result->total);
    }

    /** @test */
    public function package_price_display_works(): void
    {
        [, , $package] = $this->seedServiceWithPackage();

        $this->assertSame('50,000', $package->priceDisplay());

        $customPackage = Package::create([
            'service_id' => $package->service_id,
            'name'       => 'باقة مخصصة',
            'price'      => 0,
            'price_note' => 'حسب الطلب',
            'is_active'  => true,
        ]);

        $this->assertSame('حسب الطلب', $customPackage->priceDisplay());
    }
}
