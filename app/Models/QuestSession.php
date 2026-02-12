<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Policies\V1\Admin\QuestSessionPolicy;
use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[UsePolicy(QuestSessionPolicy::class)]
class QuestSession extends Model
{
    use HasAuthor;
    protected $fillable = [
        'author_id',
        'quest_id',
        'starts_at',
        'duration',
        'pricing_rule_id',
        'prepayment_only',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'prepayment_only' => 'boolean',
        ];
    }

    /**
     * Доступность сеанса для бронирования: нет активной брони и сеанс ещё не начался.
     */
    protected function isAvailableForBooking(): Attribute
    {
        return Attribute::get(function (): bool {
            if (! $this->starts_at->isFuture()) {
                return false;
            }

            return $this->relationLoaded('activeBooking')
                ? $this->getRelation('activeBooking') === null
                : ! $this->activeBooking()->exists();
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }

    public function pricingRule(): BelongsTo
    {
        return $this->belongsTo(PricingRule::class, 'pricing_rule_id');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'quest_session_id');
    }

    /**
     * Активная бронь (pending или confirmed). Не более одной на сеанс.
     */
    public function activeBooking(): HasOne
    {
        return $this->hasOne(Booking::class, 'quest_session_id')
            ->whereIn('status', BookingStatusEnum::activeValues());
    }
}
