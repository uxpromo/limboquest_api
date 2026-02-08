<?php

namespace App\Services;

use App\Exceptions\Auth\WrongAuthDataException;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    const TOKEN_BROWSER_APP = 'browser_app';

    /**
     * Create user model with api-token
     * @param array $data
     * @param string|null $tokenType
     * @return User|false
     */
    public static function createUser(array $data, string $tokenType = null): User|false
    {
        $model = new User();
        $model->fill($data);
        if ($model->save()) {
            static::createAuthToken($model);
            return $model;
        }
        return false;
    }

    /**
     * Return User model by email or null
     * @param string $value
     * @return Model|User|null
     */
    public static function findByEmail(string $value): Model|User|null
    {
        return User::query()
            ->where('email', Str::lower($value))
            ->first();
    }

    /**
     * Check user password
     * @param User $user
     * @param string $password
     * @return bool
     */
    public static function checkPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    public static function flushAuthTokens(User $user): int
    {
        return $user->tokens()->delete();
    }

    public static function createAuthToken(User $user, ?string $tokenType = null): string
    {
        $tokenModel = $user->createToken($tokenType ?: self::TOKEN_BROWSER_APP);
        return $tokenModel->plainTextToken;
    }

    public static function findByEmailOrFail(string $value): Model|Builder
    {
        return User::query()
            ->where('email', Str::lower($value))
            ->firstOrFail();
    }

    /**
     * @param string $email
     * @param string $password
     * @return string Auth Token
     * @throws WrongAuthDataException
     */
    public static function login(string $email, string $password): string
    {
        $user = UserService::findByEmail($email);
        if (!$user || !UserService::checkPassword($user, $password)) {
            throw new WrongAuthDataException();
        }

        if (!$user->is_active) {
            throw new WrongAuthDataException('Пользователь не активен');
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return UserService::createAuthToken($user);
    }
}
