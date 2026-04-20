<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Book;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with([
            'images',
            'category',
            'book.authors',
            'book.language',
            'book.binding',
            'book.publisher',
            'accessory',
            'giftcard',
        ])->findOrFail($id);

        $moreFromAuthor = collect();
        $showAuthorSlider = false;
        $recommended = collect();

        // Ak je to kniha, sprav aj book-specific logiku
        if ($product->book) {
            $authorIds = $product->book->authors->pluck('id');
        
            $moreFromAuthor = Book::with(['product.images', 'authors'])
                ->where('product_id', '!=', $product->id)
                ->whereHas('authors', function ($query) use ($authorIds) {
                    $query->whereIn('authors.id', $authorIds);
                })
                ->inRandomOrder()
                ->take(5)
                ->get();
        
            $showAuthorSlider = $moreFromAuthor->count() >= 5;
        
            $recommended = Book::with(['product.images', 'authors'])
                ->where('product_id', '!=', $product->id)
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        return view('products.show', compact(
            'product',
            'moreFromAuthor',
            'showAuthorSlider',
            'recommended'
        ));
    }
}