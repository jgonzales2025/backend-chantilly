<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/companies', [Company::class, 'index']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});