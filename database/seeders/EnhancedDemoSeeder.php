<?php

namespace Database\Seeders;

use App\Enums\BookingType;
use App\Enums\CategoryType;
use App\Models\Service\Category;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use App\Models\Service\Service;
use Illuminate\Database\Seeder;

/**
 * بيانات تجريبية محسّنة للتصنيفات والخدمات والباقات
 */
class EnhancedDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. إنشاء التصنيفات إذا لم تكن موجودة
        $catWedding = Category::firstOrCreate(['slug' => 'weddings'], [
            'name' => 'الأعراس',
            'type' => CategoryType::EVENTS,
            'icon' => '💒',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catEvents = Category::firstOrCreate(['slug' => 'events'], [
            'name' => 'الفعاليات',
            'type' => CategoryType::EVENTS,
            'icon' => '🎉',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catAdvertising = Category::firstOrCreate(['slug' => 'advertising'], [
            'name' => 'الإعلانات',
            'type' => CategoryType::ADS,
            'icon' => '📺',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // ── 2. إنشاء الخدمات
        $services = [
            // خدمات الأعراس
            [
                'category_id' => $catWedding->id,
                'name' => 'تصوير فيديو أعراس احترافي',
                'slug' => 'wedding-videography',
                'description' => 'تصوير فيديو احترافي للأعراس بأحدث التقنيات',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $catWedding->id,
                'name' => 'تصوير فوتوغرافي',
                'slug' => 'wedding-photography',
                'description' => 'تصوير فوتوغرافي احترافي لجميع لحظات الفرح',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $catWedding->id,
                'name' => 'تصوير وتغطية شاملة',
                'slug' => 'wedding-full-coverage',
                'description' => 'تغطية كاملة للعرس - فيديو وصور وتصوير جوي',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => true,
                'sort_order' => 3,
            ],
            // خدمات الفعاليات
            [
                'category_id' => $catEvents->id,
                'name' => 'تصوير حفلات وتجمعات',
                'slug' => 'event-recording',
                'description' => 'تصوير وتسجيل الفعاليات والحفلات',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $catEvents->id,
                'name' => 'بث مباشر للفعاليات',
                'slug' => 'event-livestream',
                'description' => 'بث مباشر احترافي للفعاليات على منصات التواصل',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => true,
                'sort_order' => 2,
            ],
            // خدمات الإعلانات
            [
                'category_id' => $catAdvertising->id,
                'name' => 'إنتاج فيديو إعلاني',
                'slug' => 'ad-video-production',
                'description' => 'إنتاج فيديوهات إعلانية احترافية',
                'booking_type' => BookingType::EVENT,
                'pricing_mode' => 'package',
                'availability_required' => false,
                'sort_order' => 1,
            ],
            [
                'category_id' => $catAdvertising->id,
                'name' => 'إدارة حسابات وسائل التواصل',
                'slug' => 'social-media-management',
                'description' => 'إدارة وتطوير حسابات وسائل التواصل الاجتماعي',
                'booking_type' => BookingType::SUBSCRIPTION,
                'pricing_mode' => 'package',
                'availability_required' => false,
                'sort_order' => 2,
            ],
        ];

        $createdServices = [];
        foreach ($services as $serviceData) {
            $service = Service::firstOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
            $createdServices[$serviceData['slug']] = $service;
        }

        // ── 3. إنشاء الباقات
        $this->createWeddingPackages($createdServices['wedding-videography']);
        $this->createWeddingPackages($createdServices['wedding-photography']);
        $this->createWeddingFullCoveragePackages($createdServices['wedding-full-coverage']);
        $this->createEventPackages($createdServices['event-recording']);
        $this->createEventPackages($createdServices['event-livestream']);
        $this->createAdPackages($createdServices['ad-video-production']);
        $this->createSocialMediaPackages($createdServices['social-media-management']);
    }

    private function createWeddingPackages(Service $service): void
    {
        $packages = [
            [
                'name' => 'باقة أساسية',
                'subtitle' => 'تصوير احترافي بسيط',
                'price' => 30000,
                'old_price' => 35000,
                'features' => ['8 ساعات تصوير', 'نسخة واحدة من الفيديو', 'موسيقى الخلفية'],
                'sort_order' => 1,
            ],
            [
                'name' => 'باقة متميزة',
                'subtitle' => 'تصوير احترافي متقدم',
                'price' => 50000,
                'old_price' => 60000,
                'features' => [
                    '12 ساعة تصوير',
                    'فيديو 4K',
                    'مونتاج احترافي',
                    'عدة نسخ من الفيديو',
                    'موسيقى مرخصة',
                    'تصوير جوي بالدرون'
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'باقة VIP',
                'subtitle' => 'تصوير شامل ومتكامل',
                'price' => 80000,
                'old_price' => 100000,
                'features' => [
                    'تصوير كامل اليوم',
                    'فيديو 4K عالي الجودة',
                    'مونتاج سينمائي متقدم',
                    'مصحح ألوان احترافي',
                    'عدة نسخ وصيغ',
                    'موسيقى مرخصة وحصرية',
                    'تصوير جوي متقدم',
                    'دعم أولوية',
                    'فيديو معاينة أسبوعي'
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['service_id' => $service->id, 'name' => $packageData['name']],
                array_merge($packageData, [
                    'description' => 'باقة ' . $packageData['name'] . ' لخدمة ' . $service->name,
                    'is_active' => true,
                ])
            );
        }
    }

    private function createWeddingFullCoveragePackages(Service $service): void
    {
        $packages = [
            [
                'name' => 'تغطية كاملة - المستوى الأول',
                'subtitle' => 'فيديو + صور',
                'price' => 60000,
                'features' => [
                    'فيديو احترافي 8 ساعات',
                    'صور عالية الجودة',
                    'مونتاج أساسي',
                    'نسخة واحدة من الفيديو'
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'تغطية كاملة - المستوى الثاني',
                'subtitle' => 'فيديو + صور + تصوير جوي',
                'price' => 100000,
                'old_price' => 120000,
                'features' => [
                    'فيديو احترافي 12 ساعة',
                    'صور عالية الجودة',
                    'مونتاج متقدم',
                    'فيديو 4K',
                    'تصوير جوي',
                    'عدة نسخ من الفيديو'
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'تغطية كاملة - الحزمة الذهبية',
                'subtitle' => 'كل شيء وأكثر',
                'price' => 150000,
                'old_price' => 180000,
                'features' => [
                    'فيديو شامل كامل اليوم',
                    'صور احترافية شاملة',
                    'مونتاج سينمائي',
                    'فيديو 4K عالي الدقة',
                    'تصوير جوي متقدم',
                    'مصحح ألوان احترافي',
                    'بث مباشر',
                    'نسخ متعددة وصيغ مختلفة',
                    'ألبوم رقمي',
                    'كتاب صور مطبوع'
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['service_id' => $service->id, 'name' => $packageData['name']],
                array_merge($packageData, [
                    'description' => 'باقة ' . $packageData['name'],
                    'is_active' => true,
                ])
            );
        }
    }

    private function createEventPackages(Service $service): void
    {
        $packages = [
            [
                'name' => 'باقة أساسية',
                'subtitle' => 'تسجيل الفعالية',
                'price' => 20000,
                'features' => ['تسجيل 4 ساعات', 'نسخة واحدة', 'بدون مونتاج إضافي'],
                'sort_order' => 1,
            ],
            [
                'name' => 'باقة متوسطة',
                'subtitle' => 'تسجيل مع مونتاج',
                'price' => 35000,
                'features' => ['تسجيل 6 ساعات', 'مونتاج أساسي', 'صوت عالي الجودة', 'نسختان'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'باقة احترافية',
                'subtitle' => 'تسجيل شامل',
                'price' => 50000,
                'features' => ['تسجيل كامل', 'مونتاج متقدم', 'تصحيح ألوان', 'صوت ستيريو', 'عدة نسخ'],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['service_id' => $service->id, 'name' => $packageData['name']],
                array_merge($packageData, [
                    'description' => 'باقة ' . $packageData['name'] . ' - ' . $service->name,
                    'is_active' => true,
                ])
            );
        }
    }

    private function createAdPackages(Service $service): void
    {
        $packages = [
            [
                'name' => 'إعلان قصير',
                'subtitle' => '15-30 ثانية',
                'price' => 25000,
                'features' => ['مدة 15-30 ثانية', 'سيناريو بسيط', 'موسيقى وصوت', 'نسخة واحدة'],
                'sort_order' => 1,
            ],
            [
                'name' => 'إعلان متوسط',
                'subtitle' => '30-60 ثانية',
                'price' => 45000,
                'features' => ['مدة 30-60 ثانية', 'سيناريو متطور', 'مونتاج احترافي', 'صوت احترافي', 'عدة نسخ'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'إعلان شامل',
                'subtitle' => '60 ثانية فأكثر',
                'price' => 70000,
                'features' => ['مدة 60+ ثانية', 'سيناريو إبداعي', 'مونتاج سينمائي', 'تصحيح ألوان', 'موسيقى حصرية', 'عدة صيغ'],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['service_id' => $service->id, 'name' => $packageData['name']],
                array_merge($packageData, [
                    'description' => 'باقة ' . $packageData['name'],
                    'is_active' => true,
                ])
            );
        }
    }

    private function createSocialMediaPackages(Service $service): void
    {
        $packages = [
            [
                'name' => 'باقة أساسية',
                'subtitle' => 'إدارة شهرية',
                'price' => 5000,
                'duration' => 30,
                'features' => ['منشر واحد يومياً', 'ردود على التعليقات', 'تقرير شهري بسيط'],
                'sort_order' => 1,
            ],
            [
                'name' => 'باقة متوسطة',
                'subtitle' => 'إدارة احترافية',
                'price' => 10000,
                'duration' => 30,
                'features' => [
                    'منشران يومياً',
                    'ردود فورية على التعليقات',
                    'استراتيجية شهرية',
                    'تقرير مفصل',
                    'تصميم محتوى'
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'باقة VIP',
                'subtitle' => 'إدارة شاملة',
                'price' => 15000,
                'duration' => 30,
                'features' => [
                    'منشران+ يومياً',
                    'ردود فورية 24/7',
                    'استراتيجية مخصصة',
                    'تقرير تحليلي مفصل',
                    'تصميم محتوى متقدم',
                    'حملات إعلانية',
                    'استشارات مباشرة'
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['service_id' => $service->id, 'name' => $packageData['name']],
                array_merge($packageData, [
                    'description' => 'باقة ' . $packageData['name'] . ' - إدارة حسابات',
                    'is_active' => true,
                ])
            );
        }
    }
}
