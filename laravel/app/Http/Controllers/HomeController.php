<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
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
