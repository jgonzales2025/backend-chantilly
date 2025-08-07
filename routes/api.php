<?php

use App\Http\Controllers\Auth\CustomerGoogleAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CakeFlavor\CakeFlavorController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductVariant\ProductVariantController;
use App\Http\Controllers\Tematica\ThemeController;
use App\Models\Company;
use App\Models\ProductType;
use Illuminate\Support\Facades\Route;

// Rutas para clientes
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);

//Rutas para  productos
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

//Rutas para variantes de producto
Route::get('/products-variant', [ProductVariantController::class, 'index']);
Route::post('/products-variant', [ProductVariantController::class, 'store']);

// Rutas para temática
Route::get('/theme', [ThemeController::class, 'index']);

//Rutas para tipo de producto
Route::get('/product-types', [ProductType::class, 'index']);

// Rutas para categorías
Route::get('/categories', [CategoryController::class, 'index']);

// Ruta para el login
Route::post('/login', [CustomerAuthController::class, 'login']);

//Ruta para la compañia
Route::get('/companies', [Company::class, 'index']);

// Ruta para pedidos
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);

// Ruta para sabores de keke
Route::get('/cake-flavors', [CakeFlavorController::class, 'index']);
Route::post('/cake-flavors', [CakeFlavorController::class, 'store']);

//Autenticación con google
Route::get('/auth/google/redirect', [CustomerGoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [CustomerGoogleAuthController::class, 'callback']);

Route::middleware('auth:sanctum')->group(function() {
    
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
});


