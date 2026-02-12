<?php

use App\Http\Controllers\V1\Admin\AuthController;
use App\Http\Controllers\V1\Admin\LocationController;
use App\Http\Controllers\V1\Admin\QuestController;
use App\Http\Controllers\V1\Admin\BookingController;
use App\Http\Controllers\V1\Admin\PricingRuleController;
use App\Http\Controllers\V1\Admin\QuestSessionController;
use Illuminate\Support\Facades\Route;

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
        'locations' => LocationController::class,
        'quests' => QuestController::class,
        'quest_sessions' => QuestSessionController::class,
        'pricing_rules' => PricingRuleController::class,
        'bookings' => BookingController::class,
    ]);
});
