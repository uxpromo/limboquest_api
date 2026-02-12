<?php

namespace App\Policies\V1\Admin;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Location $location): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Location $location): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Location $location): bool
    {
        return $user->is_admin;
    }
}
