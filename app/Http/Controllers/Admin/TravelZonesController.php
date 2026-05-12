<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\TravelZone;
use Illuminate\Http\Request;

class TravelZonesController extends Controller
{
    public function index()
    {
        $zones = TravelZone::orderBy('sort_order')->get();
        return view('admin.travel_zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.travel_zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);
        TravelZone::create($request->only('name', 'price', 'sort_order'));
        return redirect()->route('admin.travel-zones.index')->with('success', 'تمت الإضافة.');
    }

    public function edit(TravelZone $travelZone)
    {
        return view('admin.travel_zones.edit', compact('travelZone'));
    }

    public function update(Request $request, TravelZone $travelZone)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);
        $travelZone->update($request->only('name', 'price', 'sort_order'));
        return redirect()->route('admin.travel-zones.index')->with('success', 'تم التحديث.');
    }

    public function destroy(TravelZone $travelZone)
    {
        $travelZone->delete();
        return back()->with('success', 'تم الحذف.');
    }
}