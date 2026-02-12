<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Policies\V1\Admin\BookingPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(BookingPolicy::class)]
class Booking extends Model
{
    protected $fillable = [
        'quest_session_id',
        'user_id',
        'name',
        'phone',
        'email',
        'players_count',
        'status',
        'source_id',
        'pricing_snapshot',
        'total_price',
        'paid_amount',
        'manual_discount',
        'manual_discount_reason',
        'notes',
        'booking_code',
        'play_time',
        'winners',
        'hints',
        'confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatusEnum::class,
            'pricing_snapshot' => 'array',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'winners' => 'boolean',
        ];
    }

    public function questSession(): BelongsTo
    {
        return $this->belongsTo(QuestSession::class, 'quest_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
