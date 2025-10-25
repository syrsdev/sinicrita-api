<?php

// use App\Http\Controllers\v1\Chat\ChatController;

use App\Http\Controllers\v1\Dashboard\UsersController;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Auth\PasswordController;
use App\Http\Controllers\v1\Chat\CallController;
use App\Http\Controllers\v1\Chat\ChatController;
use App\Http\Controllers\v1\Dashboard\DashboardController;
use App\Http\Controllers\v1\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/register', AuthController::class . '@register');
    Route::post('/login', AuthController::class . '@login');
    Route::get('/post/guest', [PostController::class, 'guest']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/broadcasting/auth', function (Request $request) {
            return Broadcast::auth($request);
        });

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', AuthController::class . '@logout');
        Route::post('/change-password', PasswordController::class . '@ChangePassword');

        Route::prefix('/post')->group(function () {
            Route::get('/{user}', [PostController::class, 'index']);
            Route::post('/', [PostController::class, 'store']);
            Route::get('/detail/{slug}', [PostController::class, 'show']);
            Route::put('/detail/{slug}', [PostController::class, 'update']);
            Route::delete('/detail/{slug}/delete', [PostController::class, 'destroy']);
            Route::put('/detail/{slug}/status', [PostController::class, 'updateStatus']);
        });

        Route::prefix('/chat')->group(function () {
            Route::post('/session', [ChatController::class, 'createSession']);
            Route::get('/list/{user_id}', [ChatController::class, 'listChat']);
            Route::get('/detail/{session_id}', [ChatController::class, 'detailChat']);
            Route::post('/message', [ChatController::class, 'sendMessage']);
        });

        Route::prefix('call')->group(function () {
            Route::post('/initiate', [CallController::class, 'initiate']);
            Route::post('/{sessionId}/accept', [CallController::class, 'accept']);
            Route::post('/{sessionId}/candidate', [CallController::class, 'candidate']);
            Route::post('/{sessionId}/ringing', [CallController::class, 'ringing']);
            Route::post('/{sessionId}/end', [CallController::class, 'end']);
            Route::post('/{sessionId}/missed', [CallController::class, 'missed']);
        });

        Route::prefix('/dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::prefix('/users')->group(function () {
                Route::get('/', [UsersController::class, 'index']);
                Route::post('/add', [UsersController::class, 'store']);
                Route::delete('/{username}/delete', [UsersController::class, 'destroy']);
            });
        });
    });
});