<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\StockNotificationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


// ---- Public storefront ----

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::post('/stock-notifications', [StockNotificationController::class, 'store'])->name('stock-notifications.store');

// ---- Admin ----

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::patch('/products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');

        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::delete('/categories/{category}/image', [AdminCategoryController::class, 'destroyImage'])->name('categories.images.destroy');

        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });

});