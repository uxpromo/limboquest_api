<?php

namespace App\Http\Requests\V1\Admin\PricingRule;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class PricingRuleUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_for_quests' => ['required', 'boolean'],
            'is_for_gift_cards' => ['required', 'boolean'],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'base_players_count' => ['nullable', 'integer', 'min:0'],
            'surcharge_per_player' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
