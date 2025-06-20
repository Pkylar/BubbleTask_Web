
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskApiController;
use App\Http\Controllers\ProfileApiController;
use App\Http\Controllers\AuthApiController;

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

Route::middleware('auth:sanctum')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::post('/profile', [ProfileApiController::class, 'update']);
    Route::delete('/profile', [ProfileApiController::class, 'destroy']);
});

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);
    Route::post('/check-email', [AuthApiController::class, 'checkEmail']);
    
    // Email verification route (accessible via link in email)
    Route::get('/verify-email/{id}/{hash}', [AuthApiController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::post('/logout-all', [AuthApiController::class, 'logoutAll']);
        Route::get('/me', [AuthApiController::class, 'me']);
        Route::post('/refresh', [AuthApiController::class, 'refresh']);
        Route::post('/resend-verification', [AuthApiController::class, 'resendVerification']);
    });
    
    // Profile routes
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::post('/profile', [ProfileApiController::class, 'update']);
    Route::delete('/profile', [ProfileApiController::class, 'destroy']);
});