<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Jazyk
        if ($request->has('language')) {
            $query->whereIn('language', $request->language);
        }

        // Vydavatelstvo
        if ($request->has('publisher')) {
            $query->whereIn('publisher', $request->publisher);
        }

        // Vazba
        if ($request->has('cover_type')) {
            $query->whereIn('cover_type', $request->cover_type);
        }

        // Hodnotenie
        if ($request->has('rating')) {
            $minRating = min($request->rating);
            $query->where('rating', '>=', $minRating);
        }



        // 1. FILTROVANIE (Zmenšuje počet zobrazených kníh)
        // Iba skladom
        if ($request->has('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Iba Bestsellery
        if ($request->has('bestsellers')) {
            $query->where('is_bestseller', true);
        }

        // 2. ZORADENIE (Preusporiada existujúci zoznam)
        
        $sort = $request->get('sort', 'newest'); // Predvolené zoradenie bude 'najnovšie'

        switch ($sort) {
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;
            case 'most_expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('year', 'desc'); // alebo 'created_at'
                break;
        }

        $books = $query->paginate(12)->withQueryString();
        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        $relatedBooks = Book::where('genre', $book->genre)
                            ->where('id', '!=', $book->id)
                            ->take(10)
                            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}
