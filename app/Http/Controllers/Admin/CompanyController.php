<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
        public function index (){

            $setting = Company::first();


           return view('admin.company.info-company', compact('setting')) ;
        }


public function edit()
{
    $setting = Company::first();
    return view('admin.company.info-company', compact('setting'));
}

public function update(Request $request)
{
    $request->validate([
        'company_name' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email',
        'facebook' => 'nullable|url',
        'instagram' => 'nullable|url',
        'twitter' => 'nullable|url',
        'linkedin' => 'nullable|url',
        'map_embed' => 'nullable|string',
    ]);

    $setting = Company::first();
    if (!$setting) {
        $setting = new Company();
    }

    $setting->fill($request->validated());
    $setting->save();

    return redirect()->back()->with('success', 'تم تحديث معلومات الشركة بنجاح.');
}


}
