<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AuthController;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/cart/{product}', [StoreController::class, 'addToCart'])->name('cart.add');
Route::post('/buy-now/{product}', [StoreController::class, 'buyNow'])->name('buy.now');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
Route::put('/cart', [StoreController::class, 'updateCart'])->name('cart.update');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.place');

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders/new', [AdminController::class, 'orders'])->defaults('status', 'new')->name('orders.new');
    Route::get('/orders/shipping', [AdminController::class, 'orders'])->defaults('status', 'shipped')->name('orders.shipping');
    Route::get('/orders/confirmed', [AdminController::class, 'orders'])->defaults('status', 'confirmed')->name('orders.confirmed');
    Route::get('/orders/cancelled', [AdminController::class, 'orders'])->defaults('status', 'cancelled')->name('orders.cancelled');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::put('/settings', [AdminController::class, 'updateSiteSettings'])->name('settings.update');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
});
