<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Content\Company;
use App\Models\Content\PortfolioItem;
use App\Models\Content\Testimonial;
use App\Models\Service\Category;
use App\Models\Service\Service;
use App\Presenters\ServicePresenter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $cached = Cache::remember('services_index_payload', 600, function () {
            // Eager-load services (with category) and portfolio items per category upfront
            $categories = Category::query()
                ->where('is_active', true)
                ->with([
                    'services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->with('category'),
                ])
                ->orderBy('sort_order')
                ->get();

            // Experimental fallback palette for categories without a custom bg_color
            $defaultPalette = [
                '#E87C2A', // orange (brand)
                '#3B82F6', // blue
                '#8B5CF6', // violet
                '#10B981', // emerald
                '#F43F5E', // rose
                '#F59E0B', // amber
                '#06B6D4', // cyan
                '#EC4899', // pink
            ];

            $categoriesPayload = $categories->map(function (Category $cat) use ($defaultPalette) {
                static $colorIdx = 0;
                $bg = ($cat->bg_color && $cat->bg_color !== '#1a0800')
                    ? $cat->bg_color
                    : ($defaultPalette[$colorIdx++ % count($defaultPalette)]);
                [$r, $g, $b] = self::hexToRgb($bg);
                $blob1 = "rgba({$r},{$g},{$b},.65)";
                $blob2 = 'rgba(' . (int) ($r * 0.75) . ',' . (int) ($g * 0.75) . ',' . (int) ($b * 0.75) . ',.52)';
                $blob3 = 'rgba(' . min(255, $r + 40) . ',' . min(255, $g + 30) . ',' . min(255, $b + 10) . ',.38)';
                $tintSoft = "rgba({$r},{$g},{$b},0.42)";
                $tintEdge = "rgba({$r},{$g},{$b},0.14)";

                // Use eager-loaded services instead of separate queries
                $activeServices = $cat->services; // already filtered by is_active in eager load
                $serviceIds = $activeServices->pluck('id');

                $services = $activeServices->take(4)
                    ->map(function (Service $s) {
                        $slug = $s->category?->slug ?? '';

                        return [
                            'id'            => $s->id,
                            'name'          => $s->name,
                            'desc'          => $s->description ?? '',
                            'icon'          => $s->icon ?? ($s->category?->icon ?? '✦'),
                            'route'         => route('services.show', $s->slug),
                            'contact_only'  => $slug === 'production',
                        ];
                    })->values()->all();

                // ── بورتفوليو: استعلام واحد بدون inRandomOrder لتقليل الحمل ──
                $portfolio = [];
                $seenIds = [];
                $pIndex = 0;
                $catSlug = $cat->slug;
                $tagUrl = static fn (string $url): string => self::portfolioUrlForCategory($url, $catSlug, $pIndex++);

                {
                    $pItems = PortfolioItem::query()
                        ->with(['service:id,name,category_id', 'service.category:id,name'])
                        ->where('is_active', true)
                        ->where(function ($q) use ($cat, $serviceIds) {
                            $q->where('category_id', $cat->id);
                            if ($serviceIds->isNotEmpty()) {
                                $q->orWhereIn('service_id', $serviceIds);
                            }
                        })
                        ->where(function ($q) {
                            $q->where(function ($q2) {
                                $q2->where('media_type', 'image')->whereNotNull('image_path');
                            })->orWhere(function ($q2) {
                                $q2->where('media_type', 'youtube')->whereNotNull('youtube_video_id');
                            })->orWhereNotNull('preview_video_path');
                        })
                        ->inRandomOrder()
                        ->limit(3)
                        ->get();

                    foreach ($pItems as $row) {
                        if (count($portfolio) >= 3) {
                            break;
                        }
                        if (isset($seenIds[$row->id])) {
                            continue;
                        }
                        $seenIds[$row->id] = true;

                        $label = $row->service?->category?->name ?? $cat->name;
                        $title = (string) ($row->title ?? '');
                        $caption = (string) ($row->caption ?? '');

                        if ($row->media_type === 'youtube' && $row->youtube_video_id) {
                            $watch = $row->youtube_url
                                ?: ('https://www.youtube.com/watch?v=' . $row->youtube_video_id);
                            $thumb = 'https://img.youtube.com/vi/' . $row->youtube_video_id . '/hqdefault.jpg';
                            $portfolio[] = [
                                'id'                => $row->id,
                                'media_type'        => 'youtube',
                                'category_name'     => $label,
                                'title'             => $title,
                                'caption'           => $caption,
                                'youtube_url'       => $watch,
                                'youtube_video_id'  => $row->youtube_video_id,
                                'thumb_url'         => $tagUrl($thumb),
                            ];

                            continue;
                        }

                        if ($row->preview_video_path) {
                            $poster = $row->image_path ? $tagUrl(asset($row->image_path)) : null;
                            $portfolio[] = [
                                'id'              => $row->id,
                                'media_type'      => 'video',
                                'category_name'   => $label,
                                'title'           => $title,
                                'caption'         => $caption,
                                'video_url'       => $tagUrl(asset($row->preview_video_path)),
                                'poster_url'      => $poster,
                            ];

                            continue;
                        }

                        if ($row->media_type === 'image' && $row->image_path) {
                            $portfolio[] = [
                                'id'              => $row->id,
                                'media_type'      => 'image',
                                'category_name'   => $label,
                                'title'           => $title,
                                'caption'         => $caption,
                                'image_url'       => $tagUrl(asset($row->image_path)),
                            ];
                        }
                    }
                }

                $portfolio = array_slice($portfolio, 0, 3);

                return [
                    'slug'        => $cat->slug,
                    'name'        => $cat->name,
                    'description' => $cat->description ?? '',
                    'icon'        => $cat->icon ?? '📌',
                    'bg_color'    => $bg,
                    'tint_soft'   => $tintSoft,
                    'tint_edge'   => $tintEdge,
                    'blob1'       => $blob1,
                    'blob2'       => $blob2,
                    'blob3'       => $blob3,
                    'services'    => $services,
                    'portfolio'   => $portfolio,
                ];
            })->values()->all();

            $heroStrip = PortfolioItem::query()
                ->where('is_active', true)
                ->where('media_type', 'image')
                ->whereNotNull('image_path')
                ->inRandomOrder()
                ->limit(5)
                ->pluck('image_path')
                ->map(fn (?string $p) => $p ? asset($p) : null)
                ->filter()
                ->values()
                ->all();

            while (count($heroStrip) < 5) {
                $heroStrip[] = asset('img/hero-bg1.jpg');
            }
            $heroStrip = array_slice($heroStrip, 0, 5);

            $testimonials = Testimonial::query()
                ->where('status', Testimonial::STATUS_APPROVED)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->limit(5)
                ->get();

            $avgRating = Testimonial::query()
                ->where('status', Testimonial::STATUS_APPROVED)
                ->where('is_active', true)
                ->avg('rating');

            $stats = [
                'avg_rating'    => $avgRating ? round((float) $avgRating, 1) : 4.9,
                'happy_clients' => Booking::where('status', 'completed')->count() ?: (int) DB::table('clients')->count(),
                'delivery'      => '48h',
            ];

            $companyRow = Company::query()->first();
            $waNumber   = preg_replace('/\D+/', '', (string) (($companyRow?->whatsapp) ?: ($companyRow?->phone) ?: config('app.fallback_phone', '')));
            $phoneRaw   = (string) ($companyRow?->phone ?? '');

            return compact('categoriesPayload', 'heroStrip', 'testimonials', 'stats', 'waNumber', 'phoneRaw');
        });

        return view('front.services.index', [
            'categoriesPayload' => $cached['categoriesPayload'],
            'heroStrip'         => $cached['heroStrip'],
            'testimonials'      => $cached['testimonials'],
            'stats'             => $cached['stats'],
            'companyWa'         => $cached['waNumber'] ?: config('app.fallback_phone', ''),
            'companyPhoneRaw'   => $cached['phoneRaw'],
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $service->load(['activePackages.activeOptions']);

        $presenter = new ServicePresenter($service);

        return view('front.services.show', $presenter->toView());
    }

    public function packages(): View
    {
        $categories = Category::where('is_active', true)
            ->with([
                'services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'services.activePackages.activeOptions',
            ])
            ->orderBy('sort_order')
            ->get();

        return view('front.packages.index', compact('categories'));
    }

    /** يميّز رابط العرض حسب التصنيف (كسر تخزين المتصفح + تمييز لـ Alpine x-for) */
    private static function portfolioUrlForCategory(string $url, string $categorySlug, int $slot): string
    {
        $sep = str_contains($url, '?') ? '&' : '?';

        return $url . $sep . 'onxcat=' . rawurlencode($categorySlug) . '&s=' . $slot;
    }

    /** @return array{0:int,1:int,2:int} */
    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
