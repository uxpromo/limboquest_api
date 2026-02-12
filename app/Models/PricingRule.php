<?php

namespace App\Models;

use App\Policies\V1\Admin\PricingRulePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(PricingRulePolicy::class)]
class PricingRule extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'is_for_quests',
        'is_for_gift_cards',
        'base_price',
        'base_players_count',
        'surcharge_per_player',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_for_quests' => 'boolean',
            'is_for_gift_cards' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForQuests(Builder $query): Builder
    {
        return $query->where('is_for_quests', true);
    }

    public function scopeForGiftCards(Builder $query): Builder
    {
        return $query->where('is_for_gift_cards', true);
    }

    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }

    /**
     * Правило применимо для расчёта цены по количеству игроков (квесты).
     * У сертификата на сумму base_players_count и surcharge_per_player будут null.
     */
    public function isPlayerBased(): bool
    {
        return $this->base_players_count !== null && $this->surcharge_per_player !== null;
    }
}
