<?php

namespace App\Http\Resources\V1\Admin\Booking;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Booking
 */
class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quest_session_id' => $this->quest_session_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'players_count' => $this->players_count,
            'status' => $this->status?->value,
            'source_id' => $this->source_id,
            'pricing_snapshot' => $this->pricing_snapshot,
            'total_price' => $this->total_price,
            'paid_amount' => $this->paid_amount,
            'manual_discount' => $this->manual_discount,
            'manual_discount_reason' => $this->manual_discount_reason,
            'notes' => $this->notes,
            'booking_code' => $this->booking_code,
            'play_time' => $this->play_time,
            'winners' => $this->winners,
            'hints' => $this->hints,
            'confirmed_at' => $this->confirmed_at?->toIso8601String(),
            'cancelled_at' => $this->cancelled_at?->toIso8601String(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
