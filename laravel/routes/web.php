<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Models\Book;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/knihy', [BookController::class, 'index'])->name('books.index');
Route::get('/knihy/{id}', [BookController::class, 'show'])->name('books.show');

require __DIR__.'/settings.php';
