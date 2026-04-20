<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $rootKnihy = Category::where('name', 'Knihy')->first();
        $mainCategories = $rootKnihy ? $rootKnihy->children : collect();

        $query = Book::with(['product', 'authors', 'language', 'publisher', 'binding']);

        $currentMainCategory = null;
        $subCategories = collect();

        if ($request->filled('category')) {
            $selectedCat = Category::with('parent')->find($request->category);

            if ($selectedCat) {
                if ($selectedCat->category_id == $rootKnihy->id) {
                    $currentMainCategory = $selectedCat;
                    $subCategories = $selectedCat->children;

                    $ids = $subCategories->pluck('id')->push($selectedCat->id);
                    $query->whereHas('product', fn($q) => $q->whereIn('category_id', $ids));
                } else {
                    $currentMainCategory = $selectedCat->parent;
                    $subCategories = $currentMainCategory ? $currentMainCategory->children : collect();

                    $query->whereHas('product', fn($q) => $q->where('category_id', $selectedCat->id));
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->whereRaw("unaccent(name) ILIKE unaccent(?)", ["%{$search}%"]);
                })
                ->orWhereHas('authors', function ($authorQuery) use ($search) {
                    $authorQuery->whereRaw(
                        "unaccent(first_name || ' ' || last_name) ILIKE unaccent(?)",
                        ["%{$search}%"]
                    );
                });
            });
        }

        if ($request->filled('language')) {
            $query->whereHas('language', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->language);
            });
        }

        if ($request->filled('publisher')) {
            $query->whereHas('publisher', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->publisher);
            });
        }

        if ($request->filled('cover_type')) {
            $query->whereHas('binding', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->cover_type);
            });
        }

        if ($request->filled('rating')) {
            $minRating = min((array) $request->rating);
            $query->where('rating', '>=', $minRating);
        }

        if ($request->has('in_stock')) {
            $query->whereHas('product', function ($q) {
                $q->where('stock_count', '>', 0);
            });
        }

        if ($request->has('bestsellers')) {
            $query->where('is_bestseller', true);
        }

        $sort = $request->get('sort', 'newest');

        switch ($sort) {
            case 'cheapest':
                $query->join('products', 'books.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'asc')
                    ->select('books.*');
                break;

            case 'most_expensive':
                $query->join('products', 'books.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'desc')
                    ->select('books.*');
                break;

            case 'newest':
                $query->orderBy('books.year', 'desc');
                break;

            default:
                $query->orderBy('books.year', 'desc');
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        return view('books.index', compact(
            'books',
            'mainCategories',
            'subCategories',
            'currentMainCategory'
        ));
    }
}