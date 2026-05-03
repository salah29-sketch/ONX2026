<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * ⚠️ Deprecated - استخدم EnhancedDemoSeeder بدلاً من هذا
 * 
 * بيانات تجريبية لنظام الباقات الموحّد (packages + addons + pivot).
 * يحتوي على references لـ models و enums غير موجودة في البنية الحالية.
 * يفضّل تشغيل EnhancedDemoSeeder بدلاً منه.
 */
class PackageSystemSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ Seeder معطّل - الرجاء استخدام EnhancedDemoSeeder
        $this->command->info('⚠️  هذا الـ Seeder معطّل. استخدم EnhancedDemoSeeder بدلاً منه!');
        return;
        $catParties = Category::query()->create([
            'name'        => 'الحفلات',
            'slug'        => 'seed-parties-'.uniqid(),
            'description' => 'تغطية وتنظيم الحفلات',
            'icon'        => '🎉',
            'sort_order'  => 1,
            'is_active'   => true,
        ]);

        $catAds = Category::query()->create([
            'name'        => 'الإعلانات',
            'slug'        => 'seed-ads-'.uniqid(),
            'description' => 'إنتاج إعلاني وتسويق',
            'icon'        => '📣',
            'sort_order'  => 2,
            'is_active'   => true,
        ]);

        $partyServiceNames = ['تغطية فيديو حفلات', 'تصوير فوتوغرافي', 'باقة كاملة VIP'];
        $adsServiceNames = ['إعلان تلفزيوني قصير', 'حملة سوشيال', 'إنتاج استوديو'];

        $partyServices = [];
        foreach ($partyServiceNames as $i => $name) {
            $partyServices[] = Service::query()->create([
                'category_id' => $catParties->id,
                'name'        => $name,
                'slug'        => 'seed-party-svc-'.$i.'-'.uniqid(),
                'description' => 'خدمة حفلات — وصف مختصر للعرض في القوائم.',
                'is_active'   => true,
                'sort_order'  => $i,
            ]);
        }

        $adsServices = [];
        foreach ($adsServiceNames as $i => $name) {
            $adsServices[] = Service::query()->create([
                'category_id' => $catAds->id,
                'name'        => $name,
                'slug'        => 'seed-ads-svc-'.$i.'-'.uniqid(),
                'description' => 'خدمة إعلانية — وصف مختصر.',
                'is_active'   => true,
                'sort_order'  => $i,
            ]);
        }

        $addonsData = [
            ['name' => 'تصوير إضافي بالساعة', 'type' => AddonType::Fixed, 'scope' => AddonScope::Events, 'price' => 3500.0, 'unit_price' => null],
            ['name' => 'DJ احترافي', 'type' => AddonType::Fixed, 'scope' => AddonScope::Events, 'price' => 8000.0, 'unit_price' => null],
            ['name' => 'كوشة وزينة', 'type' => AddonType::Fixed, 'scope' => AddonScope::Events, 'price' => 4500.0, 'unit_price' => null],
            ['name' => 'شاشة LED كبيرة', 'type' => AddonType::Fixed, 'scope' => AddonScope::Events, 'price' => 6000.0, 'unit_price' => null],
            ['name' => 'عدد صور إضافي', 'type' => AddonType::Variable, 'scope' => AddonScope::Events, 'price' => null, 'unit_price' => 120.0],
            ['name' => 'مونتاج سينمائي مطوّل', 'type' => AddonType::Fixed, 'scope' => AddonScope::Ads, 'price' => 9000.0, 'unit_price' => null],
            ['name' => 'إعلان مدفوع تقوية (يوم)', 'type' => AddonType::Fixed, 'scope' => AddonScope::Ads, 'price' => 2500.0, 'unit_price' => null],
            ['name' => 'تعليق صوتي احترافي', 'type' => AddonType::Fixed, 'scope' => AddonScope::Ads, 'price' => 3200.0, 'unit_price' => null],
        ];

        $addons = [];
        foreach ($addonsData as $row) {
            $addons[] = Addon::query()->create([
                'name'        => $row['name'],
                'type'        => $row['type'],
                'scope'       => $row['scope'],
                'price'       => $row['price'],
                'unit_price'  => $row['unit_price'],
                'is_active'   => true,
            ]);
        }

        $allServices = array_merge($partyServices, $adsServices);

        foreach ($allServices as $svc) {
            $offer = Offer::query()->create([
                'service_id'            => $svc->id,
                'title'                 => 'عرض '.$svc->name,
                'description'           => null,
                'type'                  => 'paid',
                'pricing_type'          => 'package',
                'availability_required' => $svc->category_id === $catParties->id,
                'sort_order'            => 0,
                'is_active'             => true,
            ]);

            $isParty = $svc->category_id === $catParties->id;
            $numPackages = random_int(2, 4);
            $prices = $isParty ? [12000, 18000, 24000, 32000] : [15000, 22000, 28000, 40000];

            for ($p = 0; $p < $numPackages; $p++) {
                $feat = $isParty
                    ? ['تسليم خلال ٤٨ ساعة', 'مونتاج لون', 'موسيقى مرخّصة']
                    : ['صيغة 4K', 'مرّتين تعديل', 'نص اعلاني'];

                if ($p % 2 === 1) {
                    $feat[] = 'دعم أولوية';
                }

                $pkg = Package::query()->create([
                    'service_id'   => $svc->id,
                    'category_id'  => $svc->category_id,
                    'offer_id'     => $offer->id,
                    'name'         => ($isParty ? 'باقة حفل ' : 'باقة إعلان ').($p + 1),
                    'subtitle'     => $isParty ? 'تغطية احترافية' : 'إنتاج إعلاني',
                    'description'  => 'تفاصيل الباقة '.$svc->name.' — المستوى '.($p + 1).'.',
                    'features'     => $feat,
                    'base_price'   => $prices[$p],
                    'price'        => $prices[$p],
                    'sort_order'   => $p,
                    'is_active'    => true,
                    'is_featured'  => $p === 1,
                ]);

                $attachPool = $isParty
                    ? array_slice($addons, 0, 5)
                    : array_slice($addons, 5, 3);

                foreach ($attachPool as $idx => $ad) {
                    if (random_int(0, 1) === 0) {
                        continue;
                    }
                    $extra = $ad->type === AddonType::Variable
                        ? (random_int(0, 1) ? 100.0 : null)
                        : (random_int(0, 1) ? (float) random_int(2000, 5000) : null);

                    $pkg->addons()->attach($ad->id, [
                        'extra_price' => $extra,
                        'sort_order'  => $idx,
                        'is_active'   => true,
                    ]);
                }
            }
        }
    }
}
