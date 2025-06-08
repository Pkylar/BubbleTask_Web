<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::get('/tasks/{task}', [TaskApiController::class, 'show']);
    Route::patch('/tasks/{task}/complete', [TaskApiController::class, 'complete']);
    Route::get('/calendar', [TaskApiController::class, 'calendar']);
    Route::get('/priority', [TaskApiController::class, 'priority']);

    Route::get('/profile', [ProfileApiController::class, 'index']);
    Route::patch('/profile', [ProfileApiController::class, 'update']);
    Route::delete('/profile', [ProfileApiController::class, 'destroy']);
});