<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Guest routes (authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/register', fn () => redirect()->route('home'))->name('register');
    Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/login', fn () => redirect()->route('home'))->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->middleware('throttle:10,1');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::get('/foods', [FoodController::class, 'index'])
        ->name('foods.index');

    Route::get('/foods/create', [FoodController::class, 'create'])
        ->name('foods.create');

    Route::post('/foods', [FoodController::class, 'store'])
        ->name('foods.store');

    // UUID route model binding — non-UUID or missing IDs return 404.
    Route::get('/foods/{food}', [FoodController::class, 'show'])
        ->whereUuid('food')
        ->name('foods.show');

    Route::get('/foods/{food}/edit', [FoodController::class, 'edit'])
        ->whereUuid('food')
        ->name('foods.edit');

    Route::match(['put', 'patch'], '/foods/{food}', [FoodController::class, 'update'])
        ->whereUuid('food')
        ->name('foods.update');

    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])
        ->whereUuid('food')
        ->name('foods.destroy');
});
