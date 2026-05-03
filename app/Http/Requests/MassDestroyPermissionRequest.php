<?php

namespace App\Http\Requests;

use App\Models\Admin\Permission;
 
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPermissionRequest extends FormRequest
{
    public function authorize()
    {
        return \Illuminate\Support\Facades\Gate::allows('permission_delete');
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:permissions,id',
        ];
    }
}
