<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayaSeeder extends Seeder
{
    public function run(): void
    {
        $local    = DB::table('travel_zones')->where('name', 'محلية')->value('id');
        $nearby   = DB::table('travel_zones')->where('name', 'قريبة')->value('id');
        $far      = DB::table('travel_zones')->where('name', 'بعيدة')->value('id');

        // ولايات قريبة من سيدي بلعباس (كود 22)
        $nearbyCodes = [31, 29, 13, 48, 46, 20, 45];

        $wilayas = [
            [1,  'أدرار'],
            [2,  'الشلف'],
            [3,  'الأغواط'],
            [4,  'أم البواقي'],
            [5,  'باتنة'],
            [6,  'بجاية'],
            [7,  'بسكرة'],
            [8,  'بشار'],
            [9,  'البليدة'],
            [10, 'البويرة'],
            [11, 'تمنراست'],
            [12, 'تبسة'],
            [13, 'تلمسان'],
            [14, 'تيارت'],
            [15, 'تيزي وزو'],
            [16, 'الجزائر'],
            [17, 'الجلفة'],
            [18, 'جيجل'],
            [19, 'سطيف'],
            [20, 'سعيدة'],
            [21, 'سكيكدة'],
            [22, 'سيدي بلعباس'],
            [23, 'عنابة'],
            [24, 'قالمة'],
            [25, 'قسنطينة'],
            [26, 'المدية'],
            [27, 'مستغانم'],
            [28, 'مسيلة'],
            [29, 'معسكر'],
            [30, 'ورقلة'],
            [31, 'وهران'],
            [32, 'البيض'],
            [33, 'إليزي'],
            [34, 'برج بوعريريج'],
            [35, 'بومرداس'],
            [36, 'الطارف'],
            [37, 'تندوف'],
            [38, 'تيسمسيلت'],
            [39, 'الوادي'],
            [40, 'خنشلة'],
            [41, 'سوق أهراس'],
            [42, 'تيبازة'],
            [43, 'ميلة'],
            [44, 'عين الدفلى'],
            [45, 'النعامة'],
            [46, 'عين تيموشنت'],
            [47, 'غرداية'],
            [48, 'غليزان'],
            [49, 'تيميمون'],
            [50, 'برج باجي مختار'],
            [51, 'أولاد جلال'],
            [52, 'بني عباس'],
            [53, 'عين صالح'],
            [54, 'عين قزام'],
            [55, 'توقرت'],
            [56, 'جانت'],
            [57, 'المغير'],
            [58, 'المنيعة'],
        ];

        $rows = [];
        foreach ($wilayas as [$code, $name]) {
            if ($code === 22) {
                $zoneId  = $local;
                $isLocal = true;
            } elseif (in_array($code, $nearbyCodes, true)) {
                $zoneId  = $nearby;
                $isLocal = false;
            } else {
                $zoneId  = $far;
                $isLocal = false;
            }

            $rows[] = [
                'name'           => $name,
                'code'           => $code,
                'travel_zone_id' => $zoneId,
                'is_local'       => $isLocal,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('wilayas')->insert($rows);
    }
}
