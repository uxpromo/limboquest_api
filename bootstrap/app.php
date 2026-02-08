<?php

use App\Http\Middlewares\ForceJsonResponse;
use App\Http\Middlewares\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        //web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        using: function () {
            // Регистрация API маршрутов с версионированием
            $routesPath = __DIR__ . '/../routes';

            // Получаем все доступные версии API
            $versions = collect(glob("$routesPath/V*", GLOB_ONLYDIR))
                ->map(fn($path) => strtolower(basename($path)))
                ->filter(fn($version) => preg_match('/^v\d+$/', $version));

            foreach ($versions as $version) {
                $versionPath = "$routesPath/" . strtoupper($version);

                // Регистрируем Client API (для участников премии)
                if (file_exists("$versionPath/client/api.php")) {
                    Route::prefix("api/$version/client")
                        ->name("api.$version.client.")
                        ->middleware('api')
                        ->group("$versionPath/client/api.php");
                }

                // Регистрируем Admin API (для админов и экспертов)
                if (file_exists("$versionPath/admin/api.php")) {
                    Route::prefix("api/$version/admin")
                        ->name("api.$version.admin.")
                        ->middleware('api')
                        ->group("$versionPath/admin/api.php");
                }
            }
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->prepend([
                ForceJsonResponse::class,
            ])
            ->validateCsrfTokens(except: ['/*'])
            ->alias([
                'admin' => EnsureUserIsAdmin::class
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
