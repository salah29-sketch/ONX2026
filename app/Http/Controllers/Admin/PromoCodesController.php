<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo\PromoCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoCodesController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::orderByDesc('created_at')->paginate(20);
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        return view('admin.promo-codes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:100|unique:promo_codes,code',
            'discount_type'  => 'required|in:percent,fixed',
            'value'          => 'required|numeric|min:0',
            'min_order_value'=> 'nullable|numeric|min:0',
            'max_uses'       => 'nullable|integer|min:0',
            'valid_from'     => 'nullable|date',
            'valid_to'       => 'nullable|date|after_or_equal:valid_from',
            'is_active'      => 'nullable|boolean',
        ]);
        $data['valid_from'] = $request->filled('valid_from') ? Carbon::parse($request->valid_from) : null;
        $data['valid_to'] = $request->filled('valid_to') ? Carbon::parse($request->valid_to) : null;
        $data['is_active'] = $request->boolean('is_active', true);
        PromoCode::create($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'تم إضافة كود التخفيض.');
    }

    public function edit(PromoCode $promo_code)
    {
        $promoCode = $promo_code;
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    public function update(Request $request, PromoCode $promo_code)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:100|unique:promo_codes,code,' . $promo_code->id,
            'discount_type'  => 'required|in:percent,fixed',
            'value'          => 'required|numeric|min:0',
            'min_order_value'=> 'nullable|numeric|min:0',
            'max_uses'       => 'nullable|integer|min:0',
            'valid_from'     => 'nullable|date',
            'valid_to'       => 'nullable|date|after_or_equal:valid_from',
            'is_active'      => 'nullable|boolean',
        ]);
        $data['valid_from'] = $request->filled('valid_from') ? Carbon::parse($request->valid_from) : null;
        $data['valid_to'] = $request->filled('valid_to') ? Carbon::parse($request->valid_to) : null;
        $data['is_active'] = $request->boolean('is_active', true);
        $promo_code->update($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'تم التحديث.');
    }

    public function destroy(PromoCode $promo_code)
    {
        $promo_code->delete();
        return redirect()->route('admin.promo-codes.index')->with('success', 'تم الحذف.');
    }
}
