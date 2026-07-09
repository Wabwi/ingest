<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TrackerController::class, 'index'])->name('dashboard');
    Route::post('/meals', [TrackerController::class, 'storeMeal'])->name('meals.store');
    Route::post('/bowel-movements', [TrackerController::class, 'storeBowelMovement'])->name('bowel-movements.store');
    Route::get('/history', [TrackerController::class, 'history'])->name('history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
