<?php

namespace App\Policies\V1\Admin;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Comment $comment): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->is_admin;
    }
}
