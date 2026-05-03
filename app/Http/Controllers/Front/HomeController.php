<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Content\PortfolioItem;
use App\Models\Content\Testimonial;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // صور عشوائية — مخزنة مؤقتاً 10 دقائق لتخفيف الحمل
        $homeFeatured = Cache::remember('home_random_featured', 600, fn () =>
            PortfolioItem::query()
                ->where('is_active', true)
                ->where('media_type', 'image')
                ->whereNotNull('image_path')
                ->inRandomOrder()
                ->limit(3)
                ->get()
        );

        $testimonials = Cache::remember('home_random_testimonials', 600, fn () =>
            Testimonial::where('is_active', true)
                ->where('status', Testimonial::STATUS_APPROVED)
                ->inRandomOrder()
                ->get()
        );

        return view('front.home', compact('homeFeatured', 'testimonials'));
    }
}