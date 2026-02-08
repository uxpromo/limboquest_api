<?php

namespace App\Http\Middlewares;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Проверяет, что аутентифицированный пользователь является User (админ/эксперт)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() instanceof User) {
            abort(403, 'Access denied. Admin or expert access required.');
        }

        return $next($request);
    }
}
