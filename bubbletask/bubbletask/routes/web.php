<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route splash screen
Route::get('/splash', function () {
    return view('splash');
})->name('splash');

// Ubah route root '/' supaya langsung ke splash screen
Route::get('/', function () {
    return redirect()->route('splash');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Task routes
    Route::get('/home', [TaskController::class, 'index'])->name('home');
    Route::get('/calendar', [TaskController::class, 'calendar'])->name('calendar');
    Route::get('/priority', [TaskController::class, 'priority'])->name('priority');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    // Route untuk update status task done
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

    // Profile routes - Diperbaiki untuk konsistensi
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Load auth routes (login, register, etc)
require __DIR__.'/auth.php';