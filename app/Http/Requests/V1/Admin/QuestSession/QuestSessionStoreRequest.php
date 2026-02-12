<?php

namespace App\Http\Requests\V1\Admin\QuestSession;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class QuestSessionStoreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'quest_id' => ['required', 'integer', 'exists:quests,id'],
            'starts_at' => ['required', 'date'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'pricing_rule_id' => ['required', 'integer', 'exists:pricing_rules,id'],
            'prepayment_only' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
