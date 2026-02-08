<?php

namespace App\Traits;

use App\Notifications\ResetPasswordNotification;

trait CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset() : string
    {
        return $this->email;
    }

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $baseUrl = config('api.url_frontend');
        $path = config('api.password_reset.path', '/auth/password-reset');
        $email = $this->email;
        
        // Формируем базовые параметры
        $params = [
            'token' => $token,
            'email' => $email,
        ];
        
        // Добавляем дополнительные параметры из конфигурации
        $additionalParams = config('api.password_reset.additional_params', []);
        $params = array_merge($params, $additionalParams);
        
        // Строим URL с параметрами
        $url = $baseUrl . $path . '?' . http_build_query($params);
        
        $this->notify(new ResetPasswordNotification($url));
    }
}
