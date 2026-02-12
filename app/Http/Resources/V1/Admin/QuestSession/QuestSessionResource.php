<?php

namespace App\Http\Resources\V1\Admin\QuestSession;

use App\Models\QuestSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QuestSession
 */
class QuestSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'quest_id' => $this->quest_id,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'duration' => $this->duration,
            'pricing_rule_id' => $this->pricing_rule_id,
            'prepayment_only' => $this->prepayment_only,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
