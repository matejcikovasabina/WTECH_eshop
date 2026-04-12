<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Models\Book;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/knihy', [BookController::class, 'index'])->name('books.index');

    Route::get('/knihy/{id}', [BookController::class, 'show'])->name('books.show');
});

require __DIR__.'/settings.php';
