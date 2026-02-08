<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\LocationController;

Route::match(['get', 'post'], '/ping', fn() => response()->json('pong'));

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('password_change', [AuthController::class, 'passwordChange'])->name('password.change');
    Route::post('password_request', [AuthController::class, 'passwordRequest'])->name('password.request');
    Route::post('password_reset/{token}', [AuthController::class, 'passwordReset'])->name('password.reset');
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('user', [AuthController::class, 'user'])->name('user');
    });

    Route::apiResources([
        'locations' => LocationController::class
    ]);
});
