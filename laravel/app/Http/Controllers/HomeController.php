<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        #zatial zobrazujeme len 20 knih, 10 novych a 10 pripravovanych
        #neskor podla dakych kategorii

        $latestBookYear = Book::max('year');

        $newBooks = Book::with(['product.images', 'authors'])
            ->when($latestBookYear, function ($query) use ($latestBookYear) {
                $query->where('year', $latestBookYear);
            })
            ->latest('product_id')
            ->take(10)
            ->get();

        $recommended = Book::with(['product.images', 'authors'])
                ->inRandomOrder()
                ->take(10)
                ->get();

        return view('home', compact('newBooks', 'recommended'));
    }
}
