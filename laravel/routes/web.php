<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Models\Book;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\GiftcardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/knihy', [BookController::class, 'index'])->name('books.index');
Route::get('/knihy/{id}', [BookController::class, 'show'])->name('books.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/doplnky', [AccessoryController::class, 'index'])->name('accessories.index');
Route::get('/darcekove-poukazy', [GiftcardController::class, 'index'])->name('giftcards.index');
Route::get('/poukazky/{id}', [App\Http\Controllers\GiftcardController::class, 'show'])->name('giftcards.show');
Route::get('/doplnky/{id}', [AccessoryController::class, 'show'])->name('accessories.show');

require __DIR__.'/settings.php';
