<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [ApiAuthController::class, 'login']);

Route::get('/posts', [ApiPostController::class, 'index']);
Route::get('/posts/{id}', [ApiPostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [ApiPostController::class, 'store']);
    Route::post('/posts/{id}', [ApiPostController::class, 'update']);
    Route::delete('/posts/{id}', [ApiPostController::class, 'destroy']);

    // Admin routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [ApiUserController::class, 'index']);
        Route::get('/users/{id}', [ApiUserController::class, 'show']);
    });
});
