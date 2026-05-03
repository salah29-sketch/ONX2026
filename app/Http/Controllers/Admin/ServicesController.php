<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service\Category;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('service_access'), 403);

        $services = Service::with('category')->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        abort_unless(Gate::allows('service_create'), 403);

        $categories = Category::orderBy('sort_order')->pluck('name', 'id');
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('service_create'), 403);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'icon'         => 'nullable|string|max:50',
            'slug'         => 'nullable|string|max:255|unique:services,slug',
            'category_id'  => 'nullable|exists:categories,id',
            'description'  => 'nullable|string',
            'capabilities' => 'nullable|string',
            'hero_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active'    => 'nullable|boolean',
            'sort_order'           => 'nullable|integer',
            'bg_color'             => 'nullable|string|max:7',
            'icon_svg'             => 'nullable|string',
            'booking_type'         => 'required|string|in:event,appointment,subscription',
            'time_mode'            => 'nullable|string|in:wedding,hourly,fixed',
            'default_start_time'   => 'nullable|date_format:H:i',
            'default_end_time'     => 'nullable|date_format:H:i',
            'deposit_amount'       => 'nullable|numeric|min:0',
            'early_start_price'    => 'nullable|numeric|min:0',
            'late_end_price'       => 'nullable|numeric|min:0',
            'show_venue_selector'  => 'nullable|boolean',
            'show_wilaya_selector' => 'nullable|boolean',
        ]);

        // slug: أنشئه من الاسم إذا كان فارغاً أو "null"
        if (empty($data['slug']) || $data['slug'] === 'null') {
            $data['slug'] = Str::slug($data['name']);
        }

        // icon: أزله إذا كان فارغاً أو "null"
        if (empty($data['icon']) || $data['icon'] === 'null') {
            unset($data['icon']);
        }

        $data['category_id'] = $data['category_id'] ?: null;
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);
        $data['show_venue_selector']  = $request->boolean('show_venue_selector', false);
        $data['show_wilaya_selector'] = $request->boolean('show_wilaya_selector', false);

        // capabilities: array فارغ بدلاً من null لتجنّب DB constraint
        if (!empty($data['capabilities'])) {
            $decoded = json_decode($data['capabilities'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['capabilities' => 'الـ JSON غير صحيح، تحقق من الصياغة.'])->withInput();
            }
            $data['capabilities'] = $decoded;
        } else {
            $data['capabilities'] = [];  // ← الإصلاح: [] بدل null
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('services', 'public');
            $data['hero_image'] = 'storage/' . $path;
        } else {
            unset($data['hero_image']);
        }

        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'تم إضافة الخدمة.');
    }

    public function edit(Service $service)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $categories = Category::orderBy('sort_order')->pluck('name', 'id');
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'icon'         => 'nullable|string|max:50',
            'slug'         => 'required|string|max:255|unique:services,slug,' . $service->id,
            'category_id'  => 'nullable|exists:categories,id',
            'description'  => 'nullable|string',
            'capabilities' => 'nullable|string',
            'hero_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active'    => 'nullable|boolean',
            'sort_order'           => 'nullable|integer',
            'bg_color'             => 'nullable|string|max:7',
            'icon_svg'             => 'nullable|string',
            'booking_type'         => 'required|string|in:event,appointment,subscription',
            'time_mode'            => 'nullable|string|in:wedding,hourly,fixed',
            'default_start_time'   => 'nullable|date_format:H:i',
            'default_end_time'     => 'nullable|date_format:H:i',
            'deposit_amount'       => 'nullable|numeric|min:0',
            'early_start_price'    => 'nullable|numeric|min:0',
            'late_end_price'       => 'nullable|numeric|min:0',
            'show_venue_selector'  => 'nullable|boolean',
            'show_wilaya_selector' => 'nullable|boolean',
        ]);

        $data['category_id'] = $data['category_id'] ?: null;
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);
        $data['show_venue_selector']  = $request->boolean('show_venue_selector', false);
        $data['show_wilaya_selector'] = $request->boolean('show_wilaya_selector', false);

        // capabilities: array فارغ بدلاً من null لتجنّب DB constraint
        if (!empty($data['capabilities'])) {
            $decoded = json_decode($data['capabilities'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['capabilities' => 'الـ JSON غير صحيح، تحقق من الصياغة.'])->withInput();
            }
            $data['capabilities'] = $decoded;
        } else {
            $data['capabilities'] = [];  // ← الإصلاح: [] بدل null
        }

        if ($request->hasFile('hero_image')) {
            if ($service->hero_image && str_starts_with($service->hero_image, 'storage/')) {
                $oldPath = str_replace('storage/', '', $service->hero_image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('hero_image')->store('services', 'public');
            $data['hero_image'] = 'storage/' . $path;
        } else {
            unset($data['hero_image']);
        }

        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'تم التحديث.');
    }

    public function destroy(Service $service)
    {
        abort_unless(Gate::allows('service_delete'), 403);

        if ($service->packages()->exists()) {
            return redirect()->route('admin.services.index')
                ->with('error', 'لا يمكن حذف الخدمة لوجود باقات مرتبطة. احذف الباقات أولاً.');
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'تم الحذف.');
    }
}