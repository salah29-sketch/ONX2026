<?php

namespace App\Http\Requests;

use App\Models\Admin\User;
 
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyUserRequest extends FormRequest
{
    public function authorize()
    {
        return \Illuminate\Support\Facades\Gate::allows('user_delete');
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:users,id',
        ];
    }
}
