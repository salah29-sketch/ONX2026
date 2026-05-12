<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\Wilaya;
use App\Models\Event\TravelZone;
use Illuminate\Http\Request;

class WilayasController extends Controller
{
    public function index()
    {
        $wilayas = Wilaya::with('travelZone')->orderBy('code')->get();
        return view('admin.wilayas.index', compact('wilayas'));
    }

    public function create()
    {
        $zones = TravelZone::orderBy('sort_order')->get();
        return view('admin.wilayas.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:10|unique:wilayas,code',
            'travel_zone_id' => 'nullable|exists:travel_zones,id',
        ]);
        Wilaya::create($request->only('name', 'code', 'travel_zone_id'));
        return redirect()->route('admin.wilayas.index')->with('success', 'تمت الإضافة.');
    }

    public function edit(Wilaya $wilaya)
    {
        $zones = TravelZone::orderBy('sort_order')->get();
        return view('admin.wilayas.edit', compact('wilaya', 'zones'));
    }

    public function update(Request $request, Wilaya $wilaya)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:10|unique:wilayas,code,' . $wilaya->id,
            'travel_zone_id' => 'nullable|exists:travel_zones,id',
        ]);
        $wilaya->update($request->only('name', 'code', 'travel_zone_id'));
        return redirect()->route('admin.wilayas.index')->with('success', 'تم التحديث.');
    }

    public function destroy(Wilaya $wilaya)
    {
        $wilaya->delete();
        return back()->with('success', 'تم الحذف.');
    }
}