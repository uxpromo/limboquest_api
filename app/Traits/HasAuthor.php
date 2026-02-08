<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasAuthor
{
    /**
     * Boot the trait.
     * Автоматически устанавливает автора при создании модели.
     */
    public static function bootHasAuthor(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && is_null($model->author_id)) {
                $model->author_id = Auth::id();
            }
        });
    }

    /**
     * Получить пользователя, который является автором.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Проверить, является ли указанный пользователь автором.
     *
     * @param User|null $user
     * @return bool
     */
    public function isAuthor(?User $user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }
        return $user && $user->id === $this->author_id;
    }

    /**
     * Проверить, является ли текущий аутентифицированный пользователь автором.
     *
     * @return bool
     */
    public function isOwnedByCurrentUser(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $this->isAuthor($user);
    }

    /**
     * Установить автора модели.
     *
     * @param User|int|null $user
     * @return $this
     */
    public function setAuthor(User | int | null $user = null) : static
    {
        if ($user instanceof User) {
            $this->author_id = $user->id;
        } elseif (is_numeric($user)) {
            $this->author_id = $user;
        } elseif (is_null($user) && Auth::check()) {
            $this->author_id = Auth::id();
        }

        return $this;
    }
}
