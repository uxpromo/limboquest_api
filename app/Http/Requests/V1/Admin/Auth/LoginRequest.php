<?php

namespace App\Http\Requests\V1\Admin\Auth;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
