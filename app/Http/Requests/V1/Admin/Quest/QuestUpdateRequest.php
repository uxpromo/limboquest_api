<?php

namespace App\Http\Requests\V1\Admin\Quest;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class QuestUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'playtime' => ['nullable', 'integer', 'min:0'],
            'players_min' => ['nullable', 'integer', 'min:0'],
            'players_max' => ['nullable', 'integer', 'min:0'],
            'pricing_rule_id' => ['required', 'integer', 'exists:pricing_rules,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
            'age_rating' => ['nullable', 'string', 'max:50'],
            'is_visible' => ['required', 'boolean'],
            'is_in_dev' => ['required', 'boolean'],
            'opening_date_text' => ['nullable', 'string', 'max:255'],
            'average_time' => ['nullable', 'numeric', 'min:0'],
            'is_auto_average_time' => ['required', 'boolean'],
            'passability' => ['nullable', 'numeric', 'min:0'],
            'is_auto_passability' => ['required', 'boolean'],
            'best_time' => ['nullable', 'numeric', 'min:0'],
            'is_auto_best_time' => ['required', 'boolean'],
            'difficulty_level' => ['nullable', 'integer', 'min:0'],
            'scariness_level' => ['nullable', 'integer', 'min:0'],
            'is_bookable' => ['required', 'boolean'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
