<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\DeviceSetupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TrackerController::class, 'index'])->name('dashboard');
    Route::post('/meals', [TrackerController::class, 'storeMeal'])->name('meals.store');
    Route::post('/meals/{uuid}/update', [TrackerController::class, 'updateMeal'])->name('meals.update');
    Route::post('/meals/{uuid}/delete', [TrackerController::class, 'destroyMeal'])->name('meals.destroy');
    Route::post('/bowel-movements', [TrackerController::class, 'storeBowelMovement'])->name('bowel-movements.store');
    Route::post('/bowel-movements/{uuid}/update', [TrackerController::class, 'updateBowelMovement'])->name('bowel-movements.update');
    Route::post('/bowel-movements/{uuid}/delete', [TrackerController::class, 'destroyBowelMovement'])->name('bowel-movements.destroy');
    Route::get('/history', [TrackerController::class, 'history'])->name('history');
    
    // Offline setup routes
    Route::get('/device-setup', [DeviceSetupController::class, 'index'])->name('device-setup.index');
    Route::post('/device-setup/generate', [DeviceSetupController::class, 'generateCode'])->name('device-setup.generate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
