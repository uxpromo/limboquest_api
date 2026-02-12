<?php

namespace App\Http\Resources\V1\Admin\PricingRule;

use App\Models\PricingRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PricingRule
 */
class PricingRuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_for_quests' => $this->is_for_quests,
            'is_for_gift_cards' => $this->is_for_gift_cards,
            'base_price' => $this->base_price,
            'base_players_count' => $this->base_players_count,
            'surcharge_per_player' => $this->surcharge_per_player,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
