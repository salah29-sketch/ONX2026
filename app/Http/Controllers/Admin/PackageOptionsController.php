<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service\Package;
use App\Models\Service\PackageOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PackageOptionsController extends Controller
{
    public function index(Package $package)
    {
        abort_unless(Gate::allows('service_access'), 403);
        
        $options = $package->options()->orderBy('sort_order')->get();
        return view('admin.package-options.index', compact('package', 'options'));
    }

    public function create(Package $package)
    {
        abort_unless(Gate::allows('service_create'), 403);
        return view('admin.package-options.create', compact('package'));
    }

    public function store(Request $request, Package $package)
    {
        abort_unless(Gate::allows('service_create'), 403);

        $data = $request->validate([
            'label'         => 'required|string|max:255',
            'type'          => 'required|string|in:boolean,select,number',
            'price_effect'  => 'required|string|in:fixed,per_unit,free',
            'price'         => 'required|numeric|min:0',
            'options'       => 'nullable|string', // JSON
            'min'           => 'nullable|integer|min:0',
            'max'           => 'nullable|integer|min:0',
            'default_value' => 'nullable|string|max:255',
            'is_required'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);

        if (!empty($data['options'])) {
            $data['options'] = json_decode($data['options'], true);
        } else {
            $data['options'] = null;
        }

        $package->options()->create($data);
        return redirect()->route('admin.packages.options.index', $package->id)
            ->with('success', 'تم إضافة الخيار بنجاح.');
    }

    public function edit(Package $package, PackageOption $option)
    {
        abort_unless(Gate::allows('service_edit'), 403);
        return view('admin.package-options.edit', compact('package', 'option'));
    }

    public function update(Request $request, Package $package, PackageOption $option)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $data = $request->validate([
            'label'         => 'required|string|max:255',
            'type'          => 'required|string|in:boolean,select,number',
            'price_effect'  => 'required|string|in:fixed,per_unit,free',
            'price'         => 'required|numeric|min:0',
            'options'       => 'nullable|string',
            'min'           => 'nullable|integer|min:0',
            'max'           => 'nullable|integer|min:0',
            'default_value' => 'nullable|string|max:255',
            'is_required'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);

        if (!empty($data['options']) && is_string($data['options'])) {
            $data['options'] = json_decode($data['options'], true);
        }

        $option->update($data);
        return redirect()->route('admin.packages.options.index', $package->id)
            ->with('success', 'تم تحديث الخيار بنجاح.');
    }

    public function destroy(Package $package, PackageOption $option)
    {
        abort_unless(Gate::allows('service_delete'), 403);

        $option->delete();
        return redirect()->route('admin.packages.options.index', $package->id)
            ->with('success', 'تم حذف الخيار.');
    }
}
