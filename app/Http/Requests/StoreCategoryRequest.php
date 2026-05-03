<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueSlug = 'unique:categories,slug';
        if ($this->route('category')) {
            $uniqueSlug .= ',' . $this->route('category')->id;
        }

        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|' . $uniqueSlug,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:50',
            'bg_color'    => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم التصنيف مطلوب.',
            'slug.unique'   => 'هذا المعرف مستخدم بالفعل.',
        ];
    }
}
