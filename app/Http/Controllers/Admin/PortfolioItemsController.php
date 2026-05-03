<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\PortfolioItem;
use App\Models\Service\Category;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioItemsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $request->get('category_id', $categories->first()?->id);

        $items = PortfolioItem::query()
            ->with(['service:id,name,category_id'])
            ->when($activeCategory, fn($q) => $q->where('category_id', $activeCategory))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.portfolio-items.index', compact('items', 'categories', 'activeCategory'));
    }

    public function create()
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();
        $services   = Service::query()->where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'category_id']);

        return view('admin.portfolio-items.create', compact('categories', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'service_id'  => 'nullable|exists:services,id',
            'media_type'  => 'required|in:image,youtube,reel',
            'image'       => 'nullable|file|mimes:jpeg,jpg,png,webp,gif|max:10240',
            'youtube_url' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'reel_source' => 'nullable|in:mp4,youtube',
            'reel_url'    => 'nullable|string|max:500',
            'video'       => 'nullable|file|mimes:mp4,webm|max:102400',
        ]);

        if ($request->input('media_type') === 'image' && !$request->hasFile('image')) {
            return back()->withErrors(['image' => 'الصورة مطلوبة عندما يكون نوع الوسائط صورة.'])->withInput();
        }

        if ($request->input('media_type') === 'youtube' && !$request->filled('youtube_url')) {
            return back()->withErrors(['youtube_url' => 'رابط YouTube مطلوب.'])->withInput();
        }

        if ($request->input('media_type') === 'reel' && $request->input('reel_source') === 'youtube' && !$request->filled('reel_url')) {
            return back()->withErrors(['reel_url' => 'رابط YouTube Shorts مطلوب.'])->withInput();
        }

        $isFeatured = $request->boolean('is_featured');
        if ($isFeatured) {
            PortfolioItem::where('is_featured', true)->update(['is_featured' => false]);
        }

        // FIX #4: is_reel مشتق مباشرة من media_type — لا يعتمد على boolean()
        $isReel     = $request->input('media_type') === 'reel';
        $serviceId  = $request->filled('service_id')  ? (int) $request->service_id  : null;
        $categoryId = $request->filled('category_id') ? (int) $request->category_id : null;

        $data = [
            'title'        => $request->title,
            'category_id'  => $categoryId,
            'service_id'   => $serviceId,
            'service_type' => self::derivedServiceTypeForPortfolio($serviceId),
            'media_type'   => $request->media_type,
            'youtube_url'  => $request->input('media_type') === 'youtube' ? $request->youtube_url : null,
            'sort_order'   => $request->sort_order ?? 0,
            'is_featured'  => $isFeatured,
            'is_active'    => $request->boolean('is_active', true),
            'is_reel'      => $isReel,  // FIX #4
            'reel_source'  => $isReel ? $request->reel_source : null,
            'reel_url'     => $isReel && $request->reel_source === 'youtube' ? $request->reel_url : null,
        ];

        // FIX #2: استخراج youtube_video_id للريلز من reel_url
        if ($isReel && $request->reel_source === 'youtube' && $request->filled('reel_url')) {
            $data['youtube_video_id'] = PortfolioItem::extractYoutubeVideoId($request->reel_url);
        }

        // حفظ الصورة — لكل أنواع الوسائط (صورة + ريل)
        if ($request->hasFile('image')) {
            $ext  = $request->file('image')->getClientOriginalExtension();
            $name = 'ONX' . date('Y') . '_' . Str::random(4) . '.' . $ext;
            $path = $request->file('image')->storeAs('portfolio', $name, 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        // حفظ الفيديو mp4
        if ($isReel && $request->reel_source === 'mp4' && $request->hasFile('video')) {
            $ext  = $request->file('video')->getClientOriginalExtension();
            $name = 'ONX' . date('Y') . '_' . Str::random(4) . '.' . $ext;
            $videoPath = $request->file('video')->storeAs('portfolio/reels', $name, 'public');
            $data['video_path'] = 'storage/' . $videoPath;
        }

        PortfolioItem::create($data);

        // FIX #6: امسح cache التصنيفات عند إضافة عمل جديد
        Cache::forget('portfolio_categories');

        return redirect()
            ->route('admin.portfolio-items.index', ['category_id' => $categoryId])
            ->with('message', 'تم إنشاء العمل بنجاح.');
    }

    public function show(PortfolioItem $portfolioItem)
    {
        $portfolioItem->load(['service.category']);
        return view('admin.portfolio-items.show', compact('portfolioItem'));
    }

    public function edit(PortfolioItem $portfolioItem)
    {
        $portfolioItem->load(['service:id,category_id,name']);
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();
        $services   = Service::query()->where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'category_id']);

        return view('admin.portfolio-items.edit', compact('portfolioItem', 'categories', 'services'));
    }

    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'service_id'  => 'nullable|exists:services,id',
            'media_type'  => 'required|in:image,youtube,reel',
            'image'       => 'nullable|file|mimes:jpeg,jpg,png,webp,gif|max:10240',
            'youtube_url' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'reel_source' => 'nullable|in:mp4,youtube',
            'reel_url'    => 'nullable|string|max:500',
            'video'       => 'nullable|file|mimes:mp4,webm|max:102400',
        ]);

        if ($request->input('media_type') === 'image' && !$portfolioItem->image_path && !$request->hasFile('image')) {
            return back()->withErrors(['image' => 'الصورة مطلوبة عندما يكون نوع الوسائط صورة.'])->withInput();
        }

        if ($request->input('media_type') === 'youtube' && !$request->filled('youtube_url')) {
            return back()->withErrors(['youtube_url' => 'رابط YouTube مطلوب.'])->withInput();
        }

        if ($request->input('media_type') === 'reel' && $request->input('reel_source') === 'youtube' && !$request->filled('reel_url')) {
            return back()->withErrors(['reel_url' => 'رابط YouTube Shorts مطلوب.'])->withInput();
        }

        $isFeatured = $request->boolean('is_featured');
        if ($isFeatured) {
            PortfolioItem::where('is_featured', true)
                ->where('id', '!=', $portfolioItem->id)
                ->update(['is_featured' => false]);
        }

        // FIX #4: is_reel مشتق مباشرة من media_type
        $isReel     = $request->input('media_type') === 'reel';
        $serviceId  = $request->filled('service_id')  ? (int) $request->service_id  : null;
        $categoryId = $request->filled('category_id') ? (int) $request->category_id : null;

        $data = [
            'title'        => $request->title,
            'category_id'  => $categoryId,
            'service_id'   => $serviceId,
            'service_type' => self::derivedServiceTypeForPortfolio($serviceId),
            'media_type'   => $request->media_type,
            'youtube_url'  => $request->input('media_type') === 'youtube' ? $request->youtube_url : null,
            'sort_order'   => $request->sort_order ?? 0,
            'is_featured'  => $isFeatured,
            'is_active'    => $request->boolean('is_active', true),
            'is_reel'      => $isReel,  // FIX #4
            'reel_source'  => $isReel ? $request->reel_source : null,
            'reel_url'     => $isReel && $request->reel_source === 'youtube' ? $request->reel_url : null,
        ];

        // FIX #2: استخراج youtube_video_id للريلز من reel_url
        if ($isReel && $request->reel_source === 'youtube' && $request->filled('reel_url')) {
            $data['youtube_video_id'] = PortfolioItem::extractYoutubeVideoId($request->reel_url);
        } elseif (!$isReel || $request->reel_source !== 'youtube') {
            // امسح الـ ID لو تغير المصدر
            if ($portfolioItem->media_type !== 'youtube') {
                $data['youtube_video_id'] = null;
            }
        }

        // حفظ الصورة — لكل أنواع الوسائط (صورة + ريل)
        if ($request->hasFile('image')) {
            self::deleteStoredFile($portfolioItem->image_path);
            $ext  = $request->file('image')->getClientOriginalExtension();
            $name = 'ONX' . date('Y') . '_' . Str::random(4) . '.' . $ext;
            $path = $request->file('image')->storeAs('portfolio', $name, 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        // إذا تغيّر النوع إلى youtube — احذف الصورة
        if ($request->input('media_type') === 'youtube') {
            self::deleteStoredFile($portfolioItem->image_path);
            $data['image_path'] = null;
        }

        // حفظ الفيديو mp4
        if ($isReel && $request->reel_source === 'mp4' && $request->hasFile('video')) {
            self::deleteStoredFile($portfolioItem->video_path);
            $ext  = $request->file('video')->getClientOriginalExtension();
            $name = 'ONX' . date('Y') . '_' . Str::random(4) . '.' . $ext;
            $videoPath = $request->file('video')->storeAs('portfolio/reels', $name, 'public');
            $data['video_path'] = 'storage/' . $videoPath;
        }

        // إذا أُلغي الريل احذف الفيديو
        if (!$isReel) {
            self::deleteStoredFile($portfolioItem->video_path);
            $data['video_path'] = null;
        }

        $portfolioItem->update($data);

        // FIX #6: امسح cache التصنيفات عند تحديث عمل
        Cache::forget('portfolio_categories');

        return redirect()
            ->route('admin.portfolio-items.index', ['category_id' => $categoryId])
            ->with('message', 'تم تحديث العمل بنجاح.');
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $categoryId = $portfolioItem->category_id;
        self::deleteStoredFile($portfolioItem->image_path);
        self::deleteStoredFile($portfolioItem->video_path);
        $portfolioItem->delete();

        Cache::forget('portfolio_categories');

        return redirect()
            ->route('admin.portfolio-items.index', ['category_id' => $categoryId])
            ->with('message', 'تم حذف العمل بنجاح.');
    }

    private static function deleteStoredFile(?string $publicRelative): void
    {
        if (empty($publicRelative)) return;
        $relative = preg_replace('#^storage/#', '', $publicRelative);
        if ($relative) Storage::disk('public')->delete($relative);
    }

    private static function derivedServiceTypeForPortfolio(?int $serviceId): ?string
    {
        if (!$serviceId) return null;
        $service = Service::query()->whereKey($serviceId)->with('category:id,slug')->first();
        $catSlug = $service?->category?->slug ?? '';
        if ($catSlug === '') return null;
        return in_array($catSlug, ['business', 'production'], true) ? 'ads' : 'event';
    }
}
