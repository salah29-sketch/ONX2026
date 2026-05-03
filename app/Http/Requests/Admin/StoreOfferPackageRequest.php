<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => 'required|string|max:255',
            'subtitle'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'nullable|numeric|min:0',
            'old_price'          => 'nullable|numeric|min:0',
            'price_note'         => 'nullable|string|max:255',
            'duration'           => 'nullable|string|max:100',
            'features'           => 'nullable|string',
            'catalog_package_id' => 'nullable|integer|exists:packages,id',
            'sort_order'         => 'nullable|integer|min:0|max:999999',
            'is_featured'        => 'sometimes|boolean',
            'is_active'          => 'sometimes|boolean',
        ];
    }
}
