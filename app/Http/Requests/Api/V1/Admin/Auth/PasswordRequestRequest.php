<?php

namespace App\Http\Requests\Api\V1\admin\Auth;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class PasswordRequestRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
