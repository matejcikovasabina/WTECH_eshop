<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\GiftcardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/knihy', [BookController::class, 'index'])->name('products.index');
Route::get('/doplnky', [AccessoryController::class, 'index'])->name('accessories.index');
Route::get('/darcekove-poukazy', [GiftcardController::class, 'index'])->name('giftcards.index');

Route::get('/produkt/{id}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::get('/cart/delivery', [CheckoutController::class, 'delivery'])->name('cart.delivery');


Route::post('/cart/delivery', [CheckoutController::class, 'storeDelivery'])
    ->name('cart.delivery.store');

Route::get('/cart/payment', [CheckoutController::class, 'payment'])
    ->name('cart.payment');

Route::post('/cart/payment', [CheckoutController::class, 'storePayment'])
    ->name('cart.payment.store');

Route::get('/cart/summary', [CheckoutController::class, 'summary'])
    ->name('cart.summary');


    Route::get('/clear-cart', function () {
        session()->forget('cart');
        return redirect()->route('cart.index');
    });

require __DIR__.'/settings.php';



