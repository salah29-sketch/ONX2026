<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingPayment;
use App\Models\Booking\EventBooking;
use App\Models\Client\Client;
use App\Models\Content\Company;
use App\Models\Content\Faq;
use App\Models\Content\Testimonial;
use App\Models\Promo\PromoCode;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;
use App\Models\Worker\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCompany();
        $this->seedTravelZones();
        $this->seedWilayas();
        $this->seedVenues();

        [$catEvents, $catMarketing, $catProduction] = $this->seedCategories();
        $services = $this->seedServices($catEvents, $catMarketing, $catProduction);

        $this->seedPackagesAndOptions($services);
        $clients = $this->seedClients();
        $this->seedBookings($clients, $services);
        $this->seedPromoCodes();
        $this->seedFaqAndTestimonials();
        $this->seedWorkers();
    }

    private function seedCompany(): void
    {
        Company::firstOrCreate(['company_name' => 'ONX Edge'], [
            'address'   => 'سيدي بلعباس، الجزائر',
            'phone'     => '0540573518',
            'email'     => 'contact@onx-edge.com',
            'facebook'  => 'https://www.facebook.com/onxedge',
            'instagram' => 'https://www.instagram.com/onxedge',
        ]);
    }

    private function seedTravelZones(): void
    {
        if (DB::table('travel_zones')->count() > 0) return;

        DB::table('travel_zones')->insert([
            ['name' => 'محلية', 'price' => 0, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'قريبة', 'price' => 3000, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بعيدة', 'price' => 8000, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedWilayas(): void
    {
        if (DB::table('wilayas')->count() > 0) return;

        $local  = DB::table('travel_zones')->where('name', 'محلية')->value('id');
        $nearby = DB::table('travel_zones')->where('name', 'قريبة')->value('id');
        $far    = DB::table('travel_zones')->where('name', 'بعيدة')->value('id');

        $wilayas = [
            [22,'سيدي بلعباس'],
            [31,'وهران'],
        ];

        $rows = [];
        foreach ($wilayas as [$code, $name]) {
            $rows[] = [
                'name' => $name,
                'code' => $code,
                'travel_zone_id' => $code === 22 ? $local : $nearby,
                'is_local' => $code === 22,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('wilayas')->insert($rows);
    }

    private function seedVenues(): void
    {
        if (DB::table('venues')->count() > 0) return;

        $wilayaId = DB::table('wilayas')->where('code', 22)->value('id');

        $venues = [
            ['name' => 'قاعة النخيل'],
            ['name' => 'قاعة الأندلس'],
        ];

        foreach ($venues as $i => $v) {
            DB::table('venues')->insert([
                'name'       => $v['name'],
                'wilaya_id'  => $wilayaId,
                'is_active'  => true,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedCategories(): array
    {
        $events = Category::firstOrCreate(['slug' => 'events'], [
            'name' => 'الحفلات',
            'type' => CategoryType::EVENTS,
        ]);

        $marketing = Category::firstOrCreate(['slug' => 'marketing'], [
            'name' => 'التسويق',
            'type' => CategoryType::ADS,
        ]);

        $production = Category::firstOrCreate(['slug' => 'production'], [
            'name' => 'الإنتاج',
            'type' => CategoryType::CREATIVE,
        ]);

        return [$events, $marketing, $production];
    }

    private function seedServices($catEvents, $catMarketing, $catProduction): array
    {
        return [
            'wedding' => Service::firstOrCreate(['slug' => 'wedding'], [
                'category_id' => $catEvents->id,
                'name' => 'تصوير أعراس',
                'booking_type' => 'event',
                'pricing_mode' => 'package',
                'is_active' => true,
            ]),
            'social' => Service::firstOrCreate(['slug' => 'social'], [
                'category_id' => $catMarketing->id,
                'name' => 'إدارة صفحات',
                'booking_type' => 'subscription',
                'pricing_mode' => 'package',
                'is_active' => true,
            ]),
            'production' => Service::firstOrCreate(['slug' => 'production'], [
                'category_id' => $catProduction->id,
                'name' => 'إنتاج فيديو',
                'booking_type' => 'event',
                'pricing_mode' => 'quote',
                'is_active' => true,
            ]),
        ];
    }

    private function seedPackagesAndOptions(array $services): void
    {
        Package::firstOrCreate([
            'service_id' => $services['wedding']->id,
            'name' => 'باقة أساسية'
        ], [
            'price' => 50000,
            'is_active' => true
        ]);
    }

    private function seedClients(): array
    {
        return [
            Client::firstOrCreate(['phone' => '0550000000'], [
                'name' => 'عميل تجريبي',
                'password' => 'password'
            ])
        ];
    }

    private function seedBookings(array $clients, array $services): void
{
    Booking::firstOrCreate([
        'client_id' => $clients[0]->id,
        'service_id' => $services['wedding']->id,
    ], [
        'name'         => $clients[0]->name,
        'phone'        => $clients[0]->phone,
        'email'        => $clients[0]->email ?? null,
        'booking_type' => 'event',
        'status'       => 'pending',
        'total_price'  => 50000,
        'final_price'  => 50000,
    ]);
}
    private function seedPromoCodes(): void
    {
        PromoCode::firstOrCreate(['code' => 'TEST'], [
            'discount_type' => 'fixed',
            'value' => 1000,
            'is_active' => true,
        ]);
    }

    private function seedFaqAndTestimonials(): void
    {
        Faq::firstOrCreate(['question' => 'سؤال؟'], [
            'answer' => 'جواب',
            'is_active' => true
        ]);

        Testimonial::firstOrCreate(['client_name' => 'عميل'], [
            'content' => 'رأي',
            'rating' => 5,
            'is_active' => true
        ]);
    }

    private function seedWorkers(): void
    {
        Worker::firstOrCreate(['email' => 'worker@test.com'], [
            'name' => 'عامل',
            'password' => Hash::make('123456'),
            'is_active' => true,
        ]);
    }
}