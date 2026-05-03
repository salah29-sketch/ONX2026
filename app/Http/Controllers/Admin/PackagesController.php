<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service\Package;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PackagesController extends Controller
{
    public function index(Request $request)
    {
        // Require identical or similar permission as offers before, fallback to service_access
        abort_unless(Gate::allows('service_access'), 403);

        $packages = Package::with('service')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);
            
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        abort_unless(Gate::allows('service_create'), 403);

        $services = Service::orderBy('sort_order')->pluck('name', 'id');
        return view('admin.packages.create', compact('services'));
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('service_create'), 403);

        $data = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'name'         => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'nullable|numeric|min:0',
            'old_price'    => 'nullable|numeric|min:0',
            'price_note'   => 'nullable|string|max:255',
            'duration'     => 'nullable|string|max:255',
            'features'     => 'nullable|string',
            'is_featured'  => 'nullable|boolean',
            'is_buildable' => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_buildable'] = $request->boolean('is_buildable', false);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);

        if (!empty($data['features'])) {
            $data['features'] = json_decode($data['features'], true);
        } else {
            $data['features'] = null;
        }

        Package::create($data);
        return redirect()->route('admin.packages.index')->with('success', 'تم إضافة الباقة بنجاح.');
    }

    public function edit(Package $package)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $services = Service::orderBy('sort_order')->pluck('name', 'id');
        return view('admin.packages.edit', compact('package', 'services'));
    }

    public function update(Request $request, Package $package)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $data = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'name'         => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'nullable|numeric|min:0',
            'old_price'    => 'nullable|numeric|min:0',
            'price_note'   => 'nullable|string|max:255',
            'duration'     => 'nullable|string|max:255',
            'features'     => 'nullable|string',
            'is_featured'  => 'nullable|boolean',
            'is_buildable' => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_buildable'] = $request->boolean('is_buildable', false);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);

        if (!empty($data['features']) && is_string($data['features'])) {
            $data['features'] = json_decode($data['features'], true);
        }

        $package->update($data);
        return redirect()->route('admin.packages.index')->with('success', 'تم التحديث بنجاح.');
    }

    public function destroy(Package $package)
    {
        abort_unless(Gate::allows('service_delete'), 403);

        if ($package->bookings()->exists()) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'لا يمكن حذف الباقة لوجود حجوزات مرتبطة.');
        }

        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'تم الحذف.');
    }
}
