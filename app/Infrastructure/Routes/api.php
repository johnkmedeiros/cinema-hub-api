<?php

use App\Infrastructure\Http\Controllers\Auth\AuthController;
use App\Infrastructure\Http\Controllers\Movies\MovieController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->prefix('movies')->group(function () {
    Route::get('search', [MovieController::class, 'search']);
    // Route::get('favorites', [MovieController::class, 'listFavorites']);
    Route::post('favorites', [MovieController::class, 'addFavorite']);
    // Route::delete('favorites/{id}', [MovieController::class, 'removeFavorite']);
});
