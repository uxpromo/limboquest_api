<?php

namespace App\Models;

use App\Policies\V1\Admin\QuestPolicy;
use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(QuestPolicy::class)]
class Quest extends Model
{
    use SoftDeletes, HasAuthor;

    protected $fillable = [
        'author_id',
        'slug',
        'title',
        'subtitle',
        'playtime',
        'players_min',
        'players_max',
        'pricing_rule_id',
        'location_id',
        'short_description',
        'full_description',
        'additional_info',
        'age_rating',
        'is_visible',
        'is_in_dev',
        'opening_date_text',
        'average_time',
        'is_auto_average_time',
        'passability',
        'is_auto_passability',
        'best_time',
        'is_auto_best_time',
        'difficulty_level',
        'scariness_level',
        'is_bookable',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'is_in_dev' => 'boolean',
            'is_auto_average_time' => 'boolean',
            'is_auto_passability' => 'boolean',
            'is_auto_best_time' => 'boolean',
            'is_bookable' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function pricingRule(): BelongsTo
    {
        return $this->belongsTo(PricingRule::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(QuestSession::class, 'quest_id');
    }
}
