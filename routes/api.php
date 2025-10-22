<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\TaskController;

// Публичные маршруты (регистрация и логин)
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

// Защищённые маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('deals', DealController::class);
    Route::apiResource('tasks', TaskController::class);

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});
