<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductVariant\ProductVariantController;
use App\Http\Controllers\Tematica\ThemeController;
use App\Models\Company;
use App\Models\ProductType;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/companies', [Company::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/product-types', [ProductType::class, 'index']);
Route::get('/theme', [ThemeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products-variant', [ProductVariantController::class, 'index']);
Route::post('/products-variant', [ProductVariantController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});