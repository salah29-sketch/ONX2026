<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'type'                  => 'required|in:paid,non_paid',
            'pricing_type'          => 'required|in:package,subscription,none',
            'availability_required' => 'nullable|boolean',
            'features'              => 'nullable|string',
            'sort_order'            => 'nullable|integer|min:0',
            'is_active'             => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'عنوان العرض مطلوب.',
            'title.max'            => 'عنوان العرض لا يتجاوز 255 حرف.',
            'type.required'        => 'نوع العرض مطلوب.',
            'type.in'              => 'نوع العرض غير صالح.',
            'pricing_type.required' => 'نوع التسعير مطلوب.',
            'pricing_type.in'      => 'نوع التسعير غير صالح.',
        ];
    }
}
