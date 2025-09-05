<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerGoogleAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerForgotPasswordController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Banner\BannerSecundaryController;
use App\Http\Controllers\CakeFlavor\CakeFlavorController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\DocumentType\DocumentTypeController;
use App\Http\Controllers\Local\LocalController;
use App\Http\Controllers\MessageCustomerBot\MessageCustomerBotController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductType\ProductTypeController;
use App\Http\Controllers\ProductVariant\ProductVariantController;
use App\Http\Controllers\SaleAdvisor\SaleAdvisorController;
use App\Http\Controllers\Tematica\ThemeController;
use App\Http\Controllers\Ubigeo\UbigeoController;
use App\Models\Company;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas para clientes
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

// Rutas para  productos
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/all', [ProductController::class, 'allProducts']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/{id}/images', [ProductController::class, 'addImages']);
Route::delete('/products/{id}/images', [ProductController::class, 'deleteImage']);
Route::post('/products/{id}/set-primary-image', [ProductController::class, 'setPrimaryImage']);

// Ruta para traer accesorios
Route::get('/products-accessories', [ProductController::class, 'indexAccesories']);

// Rutas para variantes de producto
Route::get('/products-variant/all', [ProductVariantController::class, 'allProductVariants']);
Route::get('/products-variant', [ProductVariantController::class, 'index']);
Route::post('/products-variant', [ProductVariantController::class, 'store']);
Route::get('/products-variant/show/{id}', [ProductVariantController::class, 'show']);
Route::get('/products-variant/{id}', [ProductVariantController::class, 'showByPortion']);
Route::post('/products-variant/{id}/images', [ProductVariantController::class, 'addImages']);
Route::delete('/products-variant/{id}/images', [ProductVariantController::class, 'deleteImage']);
Route::post('/products-variant/{id}/set-primary-image', [ProductVariantController::class, 'setPrimaryImage']);
Route::delete('/products-variant/{id}', [ProductVariantController::class, 'destroy']);

// Rutas para temática
Route::get('/theme', [ThemeController::class, 'index']);
Route::post('/theme', [ThemeController::class, 'store']);
Route::get('/theme/{id}', [ThemeController::class, 'show']);
Route::put('/theme/{id}', [ThemeController::class, 'update']);
Route::delete('/theme/{id}', [ThemeController::class, 'destroy']);

// Rutas para tipo de producto
Route::get('/product-types', [ProductTypeController::class, 'index']);

// Rutas para categorías
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// Ruta para el login
Route::post('/login', [CustomerAuthController::class, 'login']);

// Ruta para la compañia
Route::get('/companies', [Company::class, 'index']);

// Ruta para pedidos
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);

// Ruta para sabores de keke
Route::get('/cake-flavors', [CakeFlavorController::class, 'index']);
Route::post('/cake-flavors', [CakeFlavorController::class, 'store']);
Route::get('/cake-flavors/{id}', [CakeFlavorController::class, 'show']);
Route::put('/cake-flavors/{id}', [CakeFlavorController::class, 'update']);
Route::delete('/cake-flavors/{id}', [CakeFlavorController::class, 'destroy']);

// Ruta para los tipos de documentos
Route::get('/document-types', [DocumentTypeController::class, 'index']);

// Autenticación con google
Route::get('/auth/google/redirect', [CustomerGoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [CustomerGoogleAuthController::class, 'callback']);

// Rutas para recuperación de contraseña por email
Route::post('/forgot-password', [CustomerForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [CustomerForgotPasswordController::class, 'reset']);

// Rutas para recuperación de contraseña por sms
Route::post('/recovery/send-code', [CustomerForgotPasswordController::class, 'sendRecoveryCode']);
Route::post('/recovery/verify-code', [CustomerForgotPasswordController::class, 'verifyRecoveryCode']);
Route::post('/recovery/reset-password', [CustomerForgotPasswordController::class, 'resetWithCode']);

// Rutas para los locales
Route::get('/locals/location', [LocalController::class, 'indexByLocation']);
Route::get('/locals', [LocalController::class, 'index']);
Route::post('/locals', [LocalController::class, 'store']);
Route::delete('/locals/{id}', [LocalController::class, 'destroy']);

// Rutas para los mensajes del cliente con el chatbot
Route::get('/messages-customer-bot', [MessageCustomerBotController::class, 'index']);
Route::post('/messages-customer-bot', [MessageCustomerBotController::class, 'store']);

// Ruta para las paginas
Route::get('/pages', [PageController::class, 'index']);

Route::middleware('auth:sanctum')->group(function() {
    
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json($request->user());
});

// Proceso de pago
Route::post('/session', [PaymentController::class, 'getSession']);
Route::post('/pay', [PaymentController::class, 'pay']);
Route::get('/payment-config', [PaymentController::class, 'getConfig']);
Route::post('/niubiz/pay-response', [PaymentController::class, 'payResponse']);
Route::get('/payment-data', [PaymentController::class, 'getPaymentData']);

// Rutas para los asesores de ventas
Route::get('/sale-advisors', [SaleAdvisorController::class, 'index']);
Route::post('/sale-advisors', [SaleAdvisorController::class, 'store']);

// Rutas para el ubigeo
Route::get('/departamentos', [UbigeoController::class, 'departamentos']);
Route::get('/provincias/{coddep}', [UbigeoController::class, 'provincias']);
Route::get('/distritos/{coddep}/{codpro}', [UbigeoController::class, 'distritos']);

// Rutas para el banner
Route::get('/banner', [BannerController::class, 'index']);
Route::post('/banner', [BannerController::class, 'store']);
Route::post('/banner/{id}', [BannerController::class, 'update']);
Route::post('/banners/bulk', [BannerController::class, 'bulkStore']);
Route::delete('/banner/{id}', [BannerController::class, 'destroy']);
Route::delete('/banners/all', [BannerController::class, 'destroyAll']);

// Rutas para el banner secundario
Route::get('/banner-secondary', [BannerSecundaryController::class, 'index']);
Route::post('/banner-secondary', [BannerSecundaryController::class, 'store']);
Route::post('/banner-secondary/{id}', [BannerSecundaryController::class, 'update']);
Route::delete('/banner-secondary/{id}', [BannerSecundaryController::class, 'destroy']);

// Ruta para el login del admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminAuthController::class, 'logout']);

// Ruta para el libro de reclamaciones
Route::get('/complaints', [ComplaintController::class, 'index']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/next-number', [ComplaintController::class, 'getNextComplaintNumber']);