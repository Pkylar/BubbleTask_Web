
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Task API Routes - Protected by Sanctum Authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // Standard CRUD operations
    Route::apiResource('tasks', TaskApiController::class);
    
    // Custom endpoints
    Route::post('tasks/{task}/complete', [TaskApiController::class, 'complete'])
        ->name('tasks.complete');
    
    Route::get('tasks-calendar', [TaskApiController::class, 'calendar'])
        ->name('tasks.calendar');
    
    Route::get('tasks-priority', [TaskApiController::class, 'priority'])
        ->name('tasks.priority');
    
    Route::get('tasks-completed', [TaskApiController::class, 'completed'])
        ->name('tasks.completed');
    
    Route::get('dashboard', [TaskApiController::class, 'dashboard'])
        ->name('tasks.dashboard');
});