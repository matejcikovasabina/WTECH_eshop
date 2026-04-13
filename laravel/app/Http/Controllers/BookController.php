<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['product', 'authors', 'language', 'publisher', 'binding']);

        // SEARCH BAR
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // hladanie v nazve produktu
                $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->whereRaw("unaccent(name) ILIKE unaccent(?)", ["%{$search}%"]);
                })

                // hladanie v popise knihy
                ->orWhereRaw("unaccent(description) ILIKE unaccent(?)", ["%{$search}%"])

                // hladanie v autoroch
                ->orWhereHas('authors', function ($authorQuery) use ($search) {
                    $authorQuery->whereRaw(
                        "unaccent(first_name || ' ' || last_name) ILIKE unaccent(?)",
                        ["%{$search}%"]
                    );
                });
            });
        }

        // Jazyk
        if ($request->filled('language')) {
            $query->whereHas('language', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->language);
            });
        }

        // Vydavateľstvo
        if ($request->filled('publisher')) {
            $query->whereHas('publisher', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->publisher);
            });
        }

        // Väzba
        if ($request->filled('cover_type')) {
            $query->whereHas('binding', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->cover_type);
            });
        }

        // Hodnotenie
        if ($request->filled('rating')) {
            $minRating = min((array) $request->rating);
            $query->where('rating', '>=', $minRating);
        }

        // Iba skladom
        if ($request->has('in_stock')) {
            $query->whereHas('product', function ($q) {
                $q->where('stock_count', '>', 0);
            });
        }

        // Iba bestsellery
        if ($request->has('bestsellers')) {
            $query->where('is_bestseller', true);
        }

        // Zoradenie
        $sort = $request->get('sort', 'newest');

        switch ($sort) {
            case 'cheapest':
                $query->whereHas('product')
                      ->join('products', 'books.product_id', '=', 'products.id')
                      ->orderBy('products.price', 'asc')
                      ->select('books.*');
                break;

            case 'most_expensive':
                $query->whereHas('product')
                      ->join('products', 'books.product_id', '=', 'products.id')
                      ->orderBy('products.price', 'desc')
                      ->select('books.*');
                break;

            case 'newest':
            default:
                $query->orderBy('year', 'desc');
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        return view('books.overview', compact('books'));
    }

    public function show($id)
    {
        $book = Book::with(['product', 'authors', 'language', 'publisher', 'binding'])
            ->findOrFail($id);

        $relatedBooks = Book::with(['product', 'authors'])
            ->where('product_id', '!=', $book->product_id)
            ->when($book->publisher_id, function ($q) use ($book) {
                $q->where('publisher_id', $book->publisher_id);
            })
            ->take(10)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}