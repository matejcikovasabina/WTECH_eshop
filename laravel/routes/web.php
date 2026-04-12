<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Models\Book;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/knihy/{id}', [BookController::class, 'show'])->name('books.show');
    Route::get('/knihy', function () {
        $books = Book::paginate(12);
        return view('books.index', compact('books'));
    })->name('books.index');
});

require __DIR__.'/settings.php';
