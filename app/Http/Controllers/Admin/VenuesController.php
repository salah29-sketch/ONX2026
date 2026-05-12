<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\Venue;
use App\Models\Event\Wilaya;
use Illuminate\Http\Request;

class VenuesController extends Controller
{
    public function index()
    {
        $venues = Venue::with('wilaya')->orderBy('name')->get();
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        $wilayas = Wilaya::orderBy('code')->get();
        return view('admin.venues.create', compact('wilayas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'wilaya_id' => 'nullable|exists:wilayas,id',
            'address'   => 'nullable|string|max:500',
            'capacity'  => 'nullable|integer|min:0',
        ]);
        Venue::create($request->only('name', 'wilaya_id', 'address', 'capacity'));
        return redirect()->route('admin.venues.index')->with('success', 'تمت الإضافة.');
    }

    public function edit(Venue $venue)
    {
        $wilayas = Wilaya::orderBy('code')->get();
        return view('admin.venues.edit', compact('venue', 'wilayas'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'wilaya_id' => 'nullable|exists:wilayas,id',
            'address'   => 'nullable|string|max:500',
            'capacity'  => 'nullable|integer|min:0',
        ]);
        $venue->update($request->only('name', 'wilaya_id', 'address', 'capacity'));
        return redirect()->route('admin.venues.index')->with('success', 'تم التحديث.');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();
        return back()->with('success', 'تم الحذف.');
    }
}