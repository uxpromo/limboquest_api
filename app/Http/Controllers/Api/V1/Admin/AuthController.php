<?php

namespace App\Http\Controllers\Api\V1\admin;

use App\Exceptions\Auth\WrongAuthDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Admin\Auth\PasswordChangeRequest;
use App\Http\Requests\Api\V1\Admin\Auth\PasswordRequestRequest;
use App\Http\Resources\Api\V1\Admin\User\CurrentUserResource;
use App\Models\User;
use App\Services\UserService;
use FinzorDev\Api\ApiResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    /**
     * @unauthenticated
     * @param LoginRequest $request
     * @return ApiResponse
     */
    public function login(LoginRequest $request): ApiResponse
    {
        try {
            $token = UserService::login($request->validated('email'), $request->validated('password'));
        } catch (WrongAuthDataException $e) {
            return $this->apiResponse->error(403)->withMessage($e->getMessage());
        }

        return $this->apiResponse->success()->withData([
            'token_type' => 'Bearer',
            'token' => $token,
        ])->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Access-Control-Expose-Headers' => 'Authorization'
        ]);
    }

    public function user(Request $request): ApiResponse
    {
        $user = $request->user();
        return $this->apiResponse->success()->withData(new CurrentUserResource($user, $request));
    }


    /**
     * @unauthenticated
     * @param PasswordChangeRequest $request
     * @return ApiResponse
     */
    public function passwordChange(PasswordChangeRequest $request): ApiResponse
    {
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)]);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->apiResponse->success()->withMessage('Пароль успешно изменен');
        }

        return $this->apiResponse->error()->withMessage('Ошибка изменения пароля');
    }


    /**
     * @unauthenticated
     * @param PasswordRequestRequest $request
     * @return ApiResponse
     */
    public function passwordRequest(PasswordRequestRequest $request): ApiResponse
    {
        if (!UserService::findByEmail($request->get('email'))) {
            return $this->apiResponse->error()->withMessage('Пользователя с таким E-mail не существует');
        }

        $status = Password::broker('users')->sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return $this->apiResponse->success()->withMessage('Проверочный код отправлен на ваш e-mail');
        } else {
            return $this->apiResponse->error()->withMessage('Ошибка восстановления пароля. Обратитесь в техническую поддержку');
        }
    }
}
