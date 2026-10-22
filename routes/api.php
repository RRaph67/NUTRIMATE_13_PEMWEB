<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
|
| Stateless JSON API authenticated with Laravel Sanctum bearer tokens.
|
*/

Route::prefix('v1')->group(function (): void {
    Route::middleware('throttle:10,1')->group(function (): void {
        Route::post('/auth/register', [AuthController::class, 'register'])
            ->name('api.v1.auth.register');
        Route::post('/auth/login', [AuthController::class, 'login'])
            ->name('api.v1.auth.login');
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/auth/logout', [AuthController::class, 'logout'])
            ->name('api.v1.auth.logout');
        Route::get('/auth/me', [AuthController::class, 'me'])
            ->name('api.v1.auth.me');

        Route::get('/profile', [ProfileController::class, 'show'])
            ->name('api.v1.profile.show');
        Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])
            ->name('api.v1.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('api.v1.profile.password.update');

        Route::get('/foods', [FoodController::class, 'index'])
            ->name('api.v1.foods.index');
        Route::post('/foods', [FoodController::class, 'store'])
            ->name('api.v1.foods.store');

        // UUID binding: malformed or unknown IDs resolve to 404.
        Route::get('/foods/{food}', [FoodController::class, 'show'])
            ->whereUuid('food')
            ->name('api.v1.foods.show');
        Route::match(['put', 'patch'], '/foods/{food}', [FoodController::class, 'update'])
            ->whereUuid('food')
            ->name('api.v1.foods.update');
        Route::delete('/foods/{food}', [FoodController::class, 'destroy'])
            ->whereUuid('food')
            ->name('api.v1.foods.destroy');
    });
});
