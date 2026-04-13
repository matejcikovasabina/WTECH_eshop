<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        #zatial zobrazujeme len 20 knih, 10 novych a 10 pripravovanych
        #neskor podla dakych kategorii
        $newBooks = Book::with(['product', 'authors'])->take(10)->get();
        $upcomingBooks = Book::with(['product', 'authors'])->skip(10)->take(10)->get();

        return view('home', compact('newBooks', 'upcomingBooks'));
    }
}