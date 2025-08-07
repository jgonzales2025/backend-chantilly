<?php

use App\Http\Controllers\Auth\CustomerGoogleAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductVariant\ProductVariantController;
use App\Http\Controllers\Tematica\ThemeController;
use App\Models\Company;
use App\Models\ProductType;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/companies', [Company::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/product-types', [ProductType::class, 'index']);
Route::get('/theme', [ThemeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products-variant', [ProductVariantController::class, 'index']);
Route::post('/products-variant', [ProductVariantController::class, 'store']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::middleware('auth:sanctum')->group(function() {
    
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
});

//Autenticación con google
Route::get('/auth/google/redirect', [CustomerGoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [CustomerGoogleAuthController::class, 'callback']);
