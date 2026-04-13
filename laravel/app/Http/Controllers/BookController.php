<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()
        ->join('products', 'books.product_id', '=', 'products.id')
        ->select(
            'books.*', 
            'products.price', 
            'products.name as display_name',
            'products.stock_count'
        );

        // SEARCH BAR
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("unaccent(products.name) ILIKE unaccent(?)", ["%{$search}%"])
                ->orWhereRaw("unaccent(books.description) ILIKE unaccent(?)", ["%{$search}%"])
                ->orWhereHas('authors', function($q2) use ($search) {
                    $q2->whereRaw("unaccent(name) ILIKE unaccent(?)", ["%{$search}%"]);
                });
            });
        }


        // Jazyk
        if ($request->has('language')) {
            $query->whereHas('language', function($q) use ($request) {
                $q->whereIn('name', (array)$request->language);
            });
        }

        // Vydavatelstvo
        if ($request->has('publisher')) {
            $query->whereHas('publisher', function($q) use ($request) {
                $q->whereIn('name', (array)$request->publisher);
            });
        }

        // Vazba
        if ($request->has('cover_type')) {
            $query->whereHas('coverType', function($q) use ($request) {
                $q->whereIn('name', (array)$request->cover_type);
            });
        }

        // Hodnotenie
        if ($request->has('rating')) {
            $minRating = min((array)$request->rating);
            $query->where('rating', '>=', $minRating);
        }


        // 1. FILTROVANIE
        // Iba skladom
        if ($request->has('in_stock')) {
            $query->where('products.stock_count', '>', 0);
        }

        // Iba Bestsellery
        if ($request->has('bestsellers')) {
            $query->where('is_bestseller', true);
        }

        // 2. ZORADENIE
    
        switch ($request->query('sort')) {
            case 'cheapest':
                $query->orderBy('products.price', 'asc');
                break;
            case 'most_expensive':
                $query->orderBy('products.price', 'desc');
                break;
            case 'newest':
                $query->orderBy('books.year', 'desc');
                break;
            default:
                $query->orderBy('products.id', 'desc'); // Predvolené zoradenie
                break;
        }

        // STRANKOVANIE
        $books = $query->paginate(12)->withQueryString();
        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        $book = Book::with(['authors', 'language', 'publisher', 'coverType'])->findOrFail($id);        

        $relatedBooks = Book::where('genre', $book->genre)
                            ->where('id', '!=', $book->id)
                            ->take(10)
                            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}
