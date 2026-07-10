<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceSetupController;

// Public endpoint for mobile initial setup verification
Route::post('/device-setup/verify', [DeviceSetupController::class, 'verifyCode']);

// Authenticated endpoint for synchronization of mobile logs
Route::middleware('auth.api')->post('/sync', [DeviceSetupController::class, 'syncData']);
