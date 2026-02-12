<?php

namespace App\Http\Resources\V1\Admin\Quest;

use App\Http\Resources\V1\Admin\PricingRule\PricingRuleResource;
use App\Models\Quest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Quest
 */
class QuestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'playtime' => $this->playtime,
            'players_min' => $this->players_min,
            'players_max' => $this->players_max,
            'pricing_rule_id' => $this->pricing_rule_id,
            'pricing_rule' => $this->whenLoaded('pricingRule', fn () => new PricingRuleResource($this->pricingRule)),
            'location_id' => $this->location_id,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'additional_info' => $this->additional_info,
            'age_rating' => $this->age_rating,
            'is_visible' => $this->is_visible,
            'is_in_dev' => $this->is_in_dev,
            'opening_date_text' => $this->opening_date_text,
            'average_time' => $this->average_time,
            'is_auto_average_time' => $this->is_auto_average_time,
            'passability' => $this->passability,
            'is_auto_passability' => $this->is_auto_passability,
            'best_time' => $this->best_time,
            'is_auto_best_time' => $this->is_auto_best_time,
            'difficulty_level' => $this->difficulty_level,
            'scariness_level' => $this->scariness_level,
            'is_bookable' => $this->is_bookable,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
