<?php

namespace App\Http\Requests\V1\Admin\Location;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class LocationUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'short_address' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'working_hours' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
