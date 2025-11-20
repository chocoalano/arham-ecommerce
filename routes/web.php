<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Login alias for auth redirects (required by Laravel auth)
Route::get('/login', [App\Http\Controllers\LoginRegisterController::class, 'index'])->name('login');
Route::get('/auth', [App\Http\Controllers\LoginRegisterController::class, 'index'])->name('auth');

// Dynamic Pages (catch-all route - put at the end of routes)
Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Static about route (keep for backward compatibility or use dynamic)
Route::get('/about', [App\Http\Controllers\PageController::class, 'show'])
    ->defaults('slug', 'about')
    ->name('about');

Route::name('login-register.')
    ->prefix('login-register')
    ->controller(App\Http\Controllers\LoginRegisterController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
    });

Route::name('catalog.')
    ->prefix('catalog')
    ->controller(App\Http\Controllers\CatalogController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{slug}', 'show')->name('show');
    });

Route::name('auth.')
    ->prefix('auth')
    ->middleware('auth:customer')
    ->controller(App\Http\Controllers\AuthController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/logout', 'logout')->name('logout');

        // Orders
        Route::get('/orders/{id}', 'getOrder')->name('orders.show');

        // Addresses
        Route::get('/addresses/{id}', 'getAddress')->name('addresses.show');
        Route::post('/addresses', 'storeAddress')->name('addresses.store');
        Route::put('/addresses/{id}', 'updateAddress')->name('addresses.update');
        Route::delete('/addresses/{id}', 'deleteAddress')->name('addresses.destroy');
    });
Route::name('article.')
    ->prefix('article')
    ->controller(App\Http\Controllers\ArticleController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/{slug}', 'show')->name('show');
        Route::post('/logout', 'logout')->name('logout');
    });

Route::name('cart.')
    ->prefix('cart')
    ->middleware(['auth:customer'])
    ->controller(App\Http\Controllers\CartController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/count', 'count')->name('count');
        Route::get('/summary', 'summary')->name('summary');
        Route::get('/clear', 'clear')->name('clear');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'updateItem')->name('update');
        Route::delete('/{id}', 'removeItem')->name('destroy');
        Route::get('/{slug}', 'show')->name('show');
    });

Route::name('wishlist.')
    ->prefix('wishlist')
    ->middleware(['auth:customer'])
    ->controller(App\Http\Controllers\WishlistController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/count', 'count')->name('count');
        Route::post('/', 'store')->name('store');
        Route::get('/items/{id}', 'show')->name('show')->where('id', '[0-9]+');
        Route::put('/items/{id}', 'update')->name('update')->where('id', '[0-9]+');
        Route::delete('/items/{id}', 'destroy')->name('destroy')->where('id', '[0-9]+');
    });

Route::name('checkout.')
    ->prefix('checkout')
    ->middleware(['auth:customer'])
    ->controller(App\Http\Controllers\CheckoutController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/quotes', 'quotes')->name('quotes');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/{slug}', 'show')->name('show');
        Route::get('/thankyou/{order_number}', 'thankYou')->name('thankyou');
    });

Route::name('payment.')
    ->prefix('payment')
    ->controller(App\Http\Controllers\PaymentController::class)
    ->group(function () {
        // Webhook from Midtrans (no auth required)
        Route::post('/notification', 'notification')->name('notification');

        // Customer redirect pages (auth required)
        Route::middleware(['auth:customer'])->group(function () {
            Route::get('/finish/{order_number}', 'finish')->name('finish');
            Route::get('/unfinish/{order_number}', 'unfinish')->name('unfinish');
            Route::get('/failed/{order_number}', 'failed')->name('failed');
            Route::post('/update-status/{order_number}', 'updateStatus')->name('updateStatus');
        });
    });

// RajaOngkir API Routes
Route::name('api.rajaongkir.')
    ->prefix('api/rajaongkir')
    ->controller(App\Http\Controllers\Api\RajaOngkirController::class)
    ->group(function () {
        Route::get('/provinces', 'provinces')->name('provinces');
        Route::get('/cities', 'cities')->name('cities');
        Route::get('/subdistricts', 'subdistricts')->name('subdistricts');
        Route::post('/cost', 'calculateCost')->name('cost');
        Route::post('/cost/multiple', 'calculateMultipleCosts')->name('cost.multiple');
        Route::get('/couriers', 'couriers')->name('couriers');
    });
