<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});