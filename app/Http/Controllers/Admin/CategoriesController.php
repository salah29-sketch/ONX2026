<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Service\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug']       = $data['slug'] ?: Str::slug($data['name']);
        $data['bg_color']   = $data['bg_color'] ?? '#1a0800';
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        Category::create($data);

        // FIX #6: امسح كلا الـ cache
        Cache::forget('services_index_payload');
        Cache::forget('portfolio_categories');

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $data['slug']       = $data['slug'] ?: Str::slug($data['name']);
        $data['bg_color']   = $data['bg_color'] ?? '#1a0800';
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $category->update($data);

        // FIX #6: امسح كلا الـ cache
        Cache::forget('services_index_payload');
        Cache::forget('portfolio_categories');

        return redirect()->route('admin.categories.index')->with('success', 'تم التحديث.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        // FIX #6: امسح كلا الـ cache
        Cache::forget('services_index_payload');
        Cache::forget('portfolio_categories');

        return redirect()->route('admin.categories.index')->with('success', 'تم الحذف.');
    }
}
