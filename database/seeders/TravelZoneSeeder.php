<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('travel_zones')->insert([
            ['name' => 'محلية',  'price' => 0,    'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'قريبة',  'price' => 3000, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بعيدة',  'price' => 8000, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
