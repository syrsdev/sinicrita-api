<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/register', AuthController::class . '@register');
    Route::post('/login', AuthController::class . '@login');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', AuthController::class . '@logout');

        Route::prefix('/post')->group(function () {
            Route::get('/{user}', [PostController::class, 'index']);
            Route::post('/', [PostController::class, 'store']);
            Route::get('/detail/{slug}', [PostController::class, 'show']);
            Route::put('/detail/{slug}', [PostController::class, 'update']);
            Route::delete('/detail/{slug}/delete', [PostController::class, 'destroy']);
        });
        Route::prefix('/chat')->group(function () {
            Route::post('/session', [ChatController::class, 'createSession']);
            // Route::post('/', [PostController::class, 'store']);
            // Route::get('/detail/{slug}', [PostController::class, 'show']);
            // Route::put('/detail/{slug}', [PostController::class, 'update']);
            // Route::delete('/detail/{slug}/delete', [PostController::class, 'destroy']);
        });
    });
});
