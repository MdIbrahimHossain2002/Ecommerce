<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Api\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InventoryController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\SettingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/home', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
Route::get('/pages/{slug}', [CmsController::class, 'page']);
Route::get('/faqs', [CmsController::class, 'faqs']);
Route::post('/newsletter', [CmsController::class, 'subscribe']);

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'changePassword']);

    Route::apiResource('addresses', AddressController::class)->except(['show']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{cartItem}', [CartController::class, 'update']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy']);
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy']);

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
});

// Admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('categories', AdminCategoryController::class)->except(['show']);
    Route::apiResource('brands', AdminBrandController::class)->except(['show']);
    Route::apiResource('banners', AdminBannerController::class)->except(['show']);
    Route::apiResource('coupons', AdminCouponController::class)->except(['show']);

    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{user}', [CustomerController::class, 'show']);
    Route::patch('/customers/{user}/status', [CustomerController::class, 'updateStatus']);

    Route::get('/reviews', [AdminReviewController::class, 'index']);
    Route::patch('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus']);
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply']);

    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust']);
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']);

    Route::get('/settings', [SettingController::class, 'index']);
    Route::put('/settings', [SettingController::class, 'update']);
});
