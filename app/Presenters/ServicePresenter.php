<?php

namespace App\Presenters;

use App\Models\Service\Service;
use App\Models\Content\Testimonial;
use App\Models\Content\Faq;
use Illuminate\Support\Collection;

class ServicePresenter
{
    private Service $service;
    private Collection $packages;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->packages  = $service->activePackages;
    }

    public function toView(): array
    {
        return [
            'service'     => $this->service,
            'sections'    => $this->buildSections(),
            'overlays'    => $this->buildOverlays(),
            'hasCompare'  => ! $this->isContactOnly() && $this->service->cap('compare.enabled', false),
            'compareMax'  => $this->service->cap('compare.max_items', 3),
            'contactOnly' => $this->isContactOnly(),
        ];
    }

    /** تصنيف Production: بدون باقات — تواصل مباشر فقط */
    private function isContactOnly(): bool
    {
        return $this->service->category?->slug === 'production';
    }

    // ═══════════════════════════════════════════
    //  Section orchestration
    // ═══════════════════════════════════════════

    private function buildSections(): array
    {
        $sections = [];

        $sections[] = $this->heroSection();

        foreach ($this->packageSections() as $s) {
            $sections[] = $s;
        }

        // ── جديد: آراء العملاء ──
        if ($this->service->cap('testimonials.enabled', false)) {
            if ($s = $this->testimonialsSection()) {
                $sections[] = $s;
            }
        }

        if ($portfolio = $this->portfolioSection()) {
            $sections[] = $portfolio;
        }

        // ── جديد: أسئلة شائعة ──
        if ($this->service->cap('faq.enabled', false)) {
            if ($s = $this->faqSection()) {
                $sections[] = $s;
            }
        }

        // ── مو متأكد (لا يُعرض في وضع التواصل المباشر لتجنب تكرار الروابط) ──
        if ($this->service->cap('unsure.enabled', false) && ! $this->isContactOnly()) {
            $sections[] = $this->unsureSection();
        }

        $sections[] = $this->ctaSection();

        return $sections;
    }

    private function buildOverlays(): array
    {
        if ($this->isContactOnly()) {
            return [];
        }

        $overlays = [];

        if ($this->service->cap('compare.enabled', false)) {
            $overlays[] = $this->compareOverlay();
        }

        $overlays[] = $this->bookingOverlay();

        return $overlays;
    }

    // ═══════════════════════════════════════════
    //  Hero
    // ═══════════════════════════════════════════

    private function heroSection(): array
    {
        $waText = $this->service->cap('cta.whatsapp_text');
        $waUrl  = $waText
            ? 'https://wa.me/213540573518?text=' . urlencode($waText)
            : 'https://wa.me/213540573518';

        if ($this->isContactOnly()) {
            return [
                'view' => 'front.services.sections.hero',
                'data' => [
                    'title'          => $this->service->name,
                    'description'    => $this->service->description ?? 'راسلنا لمناقشة مشروعك والتفاصيل.',
                    'badge'          => 'ONX • ' . strtoupper($this->service->slug),
                    'bgImage'        => $this->service->hero_image,
                    'travelNote'     => null,
                    'bookingUrl'     => route('contact'),
                    'contactOnly'    => true,
                    'whatsappUrl'    => $waUrl,
                    'contactUrl'     => route('contact'),
                    'packagesAnchor' => null,
                ],
            ];
        }

        return [
            'view' => 'front.services.sections.hero',
            'data' => [
                'title'          => $this->service->name,
                'description'    => $this->service->description ?? 'اختر الباقة المناسبة واترك الباقي علينا.',
                'badge'          => 'ONX • ' . strtoupper($this->service->slug),
                'bgImage'        => $this->service->hero_image,
                'travelNote'     => $this->service->cap('travel_note'),
                'bookingUrl'     => route('booking') . '?service_id=' . $this->service->id,
                'contactOnly'    => false,
                'whatsappUrl'    => $waUrl,
                'contactUrl'     => route('contact'),
                'packagesAnchor' => '#packages',
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  Offers
    // ═══════════════════════════════════════════

    private function packageSections(): array
    {
        if ($this->isContactOnly() || $this->packages->isEmpty()) {
            return [];
        }

        return [$this->buildPackagesSection()];
    }

    private function buildPackagesSection(): array
    {
        $packages    = $this->orderPackages($this->packages);
        $allFeatures = $this->collectFeatures($packages);
        $showCompare = $this->service->cap('compare.enabled', false);

        $cards = $packages->map(function ($pkg) use ($showCompare) {
            $features = is_array($pkg->features) ? $pkg->features : (json_decode($pkg->features, true) ?? []);

            return [
                'id'            => $pkg->id,
                'serviceId'     => $this->service->id,
                'name'          => $pkg->name,
                'subtitle'      => $pkg->subtitle,
                'description'   => $pkg->description,
                'price'         => $pkg->price !== null ? (float) $pkg->price : null,
                'oldPrice'      => $pkg->old_price !== null ? (float) $pkg->old_price : null,
                'priceNote'     => $pkg->price_note,
                'priceDisplay'  => $pkg->priceDisplay(),
                'currency'      => $pkg->currencyLabel(),
                'isFeatured'    => (bool) $pkg->is_featured,
                'featuredLabel' => $pkg->subtitle ?: 'الأكثر طلبًا',
                'features'      => $features,
                'bookingLabel'  => $this->service->isSubscription() ? 'ابدأ الاشتراك' : ($this->service->cap('booking.label', 'احجز الآن')),
                'typeBadge'     => null,
                'showCompare'   => $showCompare,
                'bookingJs'     => [
                    'id'             => $pkg->id,
                    'serviceId'      => $this->service->id,
                    'name'           => $pkg->name,
                    'price'          => $pkg->price !== null ? (float) $pkg->price : null,
                    'priceText'      => $pkg->fullPriceDisplay(),
                    'needsCalendar'  => $this->service->needsCalendar(),
                    'isSubscription' => $this->service->isSubscription(),
                ],
            ];
        })->values()->all();

        return [
            'view' => 'front.services.sections.packages',
            'data' => [
                'sectionId'   => 'packages',
                'title'       => 'الباقات المتاحة',
                'subtitle'    => $this->service->description ?? '',
                'cards'       => $cards,
                'allFeatures' => $allFeatures,
                'count'       => count($cards),
            ],
        ];
    }



    // ═══════════════════════════════════════════
    //  Testimonials (جديد)
    // ═══════════════════════════════════════════

    private function testimonialsSection(): ?array
    {
        $testimonials = Testimonial::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        if ($testimonials->isEmpty()) {
            return null;
        }

        return [
            'view' => 'front.services.sections.testimonials',
            'data' => ['testimonials' => $testimonials],
        ];
    }

    // ═══════════════════════════════════════════
    //  FAQ (جديد)
    // ═══════════════════════════════════════════

    private function faqSection(): ?array
    {
        $faqs = Faq::orderBy('sort_order')->get();

        if ($faqs->isEmpty()) {
            return null;
        }

        return [
            'view' => 'front.services.sections.faq',
            'data' => ['faqs' => $faqs],
        ];
    }

    // ═══════════════════════════════════════════
    //  Unsure (جديد)
    // ═══════════════════════════════════════════

    private function unsureSection(): array
    {
        $waText = $this->service->cap('cta.whatsapp_text');

        return [
            'view' => 'front.services.sections.unsure',
            'data' => [
                'whatsappUrl' => $waText
                    ? 'https://wa.me/213540573518?text=' . urlencode($waText)
                    : 'https://wa.me/213540573518',
                'bookingUrl'  => route('booking') . '?service_id=' . $this->service->id,
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  Portfolio
    // ═══════════════════════════════════════════

    private function portfolioSection(): ?array
    {
        if (!$this->service->cap('portfolio.enabled', false)) {
            return null;
        }

        $items = $this->service->portfolioItems()
            ->where('is_active', true)
            ->where('media_type', 'image')
            ->whereNotNull('image_path')
            ->inRandomOrder()
            ->limit($this->service->cap('portfolio.limit', 6))
            ->get();

        if ($items->isEmpty()) {
            return null;
        }

        return [
            'view' => 'front.services.sections.portfolio',
            'data' => [
                'items'       => $items,
                'title'       => $this->service->cap('portfolio.title', 'أعمال مختارة'),
                'subtitle'    => $this->service->cap('portfolio.subtitle', 'نماذج من أعمالنا'),
                'description' => $this->service->cap('portfolio.description', ''),
                'badge'       => $this->service->cap('portfolio.badge', 'WORK'),
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  CTA
    // ═══════════════════════════════════════════

    private function ctaSection(): array
    {
        $waText = $this->service->cap('cta.whatsapp_text');
        $waUrl  = $waText
            ? 'https://wa.me/213540573518?text=' . urlencode($waText)
            : 'https://wa.me/213540573518';

        if ($this->isContactOnly()) {
            return [
                'view' => 'front.services.sections.cta',
                'data' => [
                    'title'       => $this->service->cap('cta.title', 'مشروع إنتاج؟'),
                    'description' => $this->service->cap('cta.description', 'راسلنا على واتساب أو عبر نموذج التواصل لمناقشة التفاصيل.'),
                    'bookingUrl'  => null,
                    'whatsappUrl' => $waUrl,
                    'contactOnly' => true,
                ],
            ];
        }

        return [
            'view' => 'front.services.sections.cta',
            'data' => [
                'title'       => $this->service->cap('cta.title', 'جاهز تحجز؟'),
                'description' => $this->service->cap('cta.description', 'تواصل معنا أو اذهب لصفحة الحجز.'),
                'bookingUrl'  => route('booking') . '?service_id=' . $this->service->id,
                'whatsappUrl' => $waUrl,
                'contactOnly' => false,
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  Compare overlay
    // ═══════════════════════════════════════════

    private function compareOverlay(): array
    {
        return [
            'view' => 'front.services.sections.compare',
            'data' => [
                'maxItems' => $this->service->cap('compare.max_items', 3),
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  Booking overlay
    // ═══════════════════════════════════════════

    private function bookingOverlay(): array
    {
        $allPackages = [];

        foreach ($this->packages as $pkg) {
            $allPackages[] = [
                'id'             => $pkg->id,
                'serviceId'      => $this->service->id,
                'name'           => $pkg->name,
                'price'          => $pkg->price !== null ? (float) $pkg->price : null,
                'priceText'      => $pkg->fullPriceDisplay(),
                'featured'       => (bool) $pkg->is_featured,
                'needsCalendar'  => $this->service->needsCalendar(),
                'isSubscription' => $this->service->isSubscription(),
            ];
        }

        $anyNeedsCalendar = $this->service->needsCalendar();

        return [
            'view' => 'front.services.sections.booking',
            'data' => [
                'serviceId'     => $this->service->id,
                'serviceSlug'   => $this->service->slug,
                'needsCalendar' => $anyNeedsCalendar,
                'packagesJson'  => $allPackages,
            ],
        ];
    }

    // ═══════════════════════════════════════════
    //  Helpers
    // ═══════════════════════════════════════════

    private function orderPackages(Collection $packages): Collection
    {
        $featured = $packages->firstWhere('is_featured', true);
        $others   = $packages->where('is_featured', false)->values();
        $half     = (int) ceil($others->count() / 2);

        return collect()
            ->merge($others->slice(0, $half))
            ->when($featured, fn ($c) => $c->push($featured))
            ->merge($others->slice($half));
    }

    private function collectFeatures(Collection $packages): array
    {
        return $packages->flatMap(function ($pkg) {
            $f = $pkg->features;
            return is_array($f) ? $f : (json_decode($f, true) ?? []);
        })->unique()->values()->toArray();
    }
}