<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Models\Book;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/knihy', [BookController::class, 'index'])->name('books.overview');
Route::get('/knihy/{id}', [BookController::class, 'show'])->name('books.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

require __DIR__.'/settings.php';
