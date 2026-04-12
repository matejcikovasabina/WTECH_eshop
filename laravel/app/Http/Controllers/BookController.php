<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->has('language')) {
            $query->whereIn('language', $request->language);
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
