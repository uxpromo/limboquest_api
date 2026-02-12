<?php

namespace App\Http\Requests\V1\Admin\Booking;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class BookingUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'quest_session_id' => ['required', 'integer', 'exists:quest_sessions,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'players_count' => ['required', 'integer', 'min:1', 'max:255'],
            'status' => ['required', 'string', Rule::in(['pending', 'confirmed', 'cancelled', 'completed', 'absent'])],
            'source_id' => ['nullable', 'string', 'max:255'],
            'pricing_snapshot' => ['nullable', 'array'],
            'total_price' => ['required', 'integer', 'min:0'],
            'paid_amount' => ['nullable', 'integer', 'min:0'],
            'manual_discount' => ['nullable', 'integer', 'min:0'],
            'manual_discount_reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'booking_code' => ['required', 'string', 'max:255', Rule::unique('bookings', 'booking_code')->ignore($this->route('booking'))],
            'play_time' => ['nullable', 'integer', 'min:0'],
            'winners' => ['nullable', 'boolean'],
            'hints' => ['nullable', 'integer', 'min:0'],
            'confirmed_at' => ['nullable', 'date'],
            'cancelled_at' => ['nullable', 'date'],
        ];
    }
}
