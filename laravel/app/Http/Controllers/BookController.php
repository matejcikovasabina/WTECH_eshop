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
