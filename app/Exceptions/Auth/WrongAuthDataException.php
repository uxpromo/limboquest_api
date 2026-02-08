<?php

namespace App\Exceptions\Auth;

use Exception;

class WrongAuthDataException extends Exception
{
    //todo Убрать хардкод сообщения
    public function __construct(string $message = "Неправильный E-mail или пароль", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
