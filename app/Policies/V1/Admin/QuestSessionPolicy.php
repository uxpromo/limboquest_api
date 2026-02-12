<?php

namespace App\Policies\V1\Admin;

use App\Models\QuestSession;
use App\Models\User;

class QuestSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, QuestSession $questSession): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, QuestSession $questSession): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, QuestSession $questSession): bool
    {
        return $user->is_admin;
    }
}
