<?php

namespace App\Policies\V1\Admin;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->is_admin;
    }
}
