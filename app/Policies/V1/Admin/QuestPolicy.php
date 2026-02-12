<?php

namespace App\Policies\V1\Admin;

use App\Models\Quest;
use App\Models\User;

class QuestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Quest $quest): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Quest $quest): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Quest $quest): bool
    {
        return $user->is_admin;
    }
}
