<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class BookController extends Controller

{
    public function index(Request $request)
    {

        $rootKnihy = Category::where('name', 'Knihy')->first();
        $mainCategories = $rootKnihy ? $rootKnihy->children : collect();

        // 2. Query pre knihy
        $query = Book::with(['product', 'authors', 'language', 'publisher', 'binding']);

        // 3. Logika pre podkategorie
        $currentMainCategory = null;
        $subCategories = collect();

        if ($request->filled('category')) {
            $selectedCat = Category::with('parent')->find($request->category);

            if ($selectedCat) {
                // ak je vybrana kategoria hlavna (jej rodic je "Knihy")
                if ($selectedCat->category_id == $rootKnihy->id) {
                    $currentMainCategory = $selectedCat;
                    $subCategories = $selectedCat->children;
                    
                    // Zobrazime produkty z tejto kategórie AJ vsetky jej deti
                    $ids = $subCategories->pluck('id')->push($selectedCat->id);
                    $query->whereHas('product', fn($q) => $q->whereIn('category_id', $ids));
                } 
                // ak uz vybrana konkretna podkategoria
                else {
                    $currentMainCategory = $selectedCat->parent;
                    $subCategories = $currentMainCategory ? $currentMainCategory->children : collect();
                    
                    // zobrazime produkty z tejto podkaterohie
                    $query->whereHas('product', fn($q) => $q->where('category_id', $selectedCat->id));
                }
            }
        }

        // SEARCH BAR
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // hladanie v nazve produktu
                $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->whereRaw("unaccent(name) ILIKE unaccent(?)", ["%{$search}%"]);
                })

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
                $query->orderBy('books.year', 'desc');
                break;
            default:
                $query->orderBy('year', 'desc');
                break;
        }

        // STRANKOVANIE
        $books = $query->paginate(12)->withQueryString();

        return view('books.index', compact(
            'books', 
            'mainCategories', 
            'subCategories', 
            'currentMainCategory'
        ));
    }

    public function show($id)
    {
        $book = \App\Models\Book::with([
            'product',
            'authors',
            'language',
            'binding',
            'publisher'
        ])->findOrFail($id);

        $authorIds = $book->authors->pluck('id');

        $moreFromAuthor = \App\Models\Book::with(['product', 'authors'])
            ->where('product_id', '!=', $book->product_id)
            ->whereHas('authors', function ($query) use ($authorIds) {
                $query->whereIn('authors.id', $authorIds);
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        $showAuthorSlider = $moreFromAuthor->count() >= 5;

        $recommended = \App\Models\Book::with(['product', 'authors'])
            ->where('product_id', '!=', $book->product_id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('books.show', compact(
            'book',
            'moreFromAuthor',
            'showAuthorSlider',
            'recommended'
        ));
    }
}