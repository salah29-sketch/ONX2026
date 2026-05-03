<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageBuilderSeeder extends Seeder
{
    public function run(): void
    {
        // المدد الافتراضية
        DB::table('package_builder_durations')->insert([
            ['name' => '2 ساعة',     'price' => 8000,  'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '4 ساعات',    'price' => 14000, 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'يوم كامل',   'price' => 22000, 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // الإضافات الافتراضية
        DB::table('package_builder_addons')->insert([
            ['name' => 'Drone',           'icon' => '🚁', 'price' => 5000, 'description' => 'تصوير جوي بطائرة مسيّرة',        'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reels للسوشيال', 'icon' => '📱', 'price' => 3000, 'description' => 'مقاطع قصيرة لانستقرام وتيك توك', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تغطية ثانية',    'icon' => '🎥', 'price' => 7000, 'description' => 'كاميرا إضافية لزوايا متعددة',     'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'البوم رقمي',     'icon' => '💿', 'price' => 2000, 'description' => 'كل الصور على USB أو رابط سحابي',  'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
