<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $rootKnihy = Category::where('name', 'Knihy')->first();
        $mainCategories = $rootKnihy ? $rootKnihy->children : collect();

        $query = Product::with([
            'images',
            'book.authors',
            'book.language',
            'book.publisher',
            'book.binding'
        ])->whereHas('book');

        $currentMainCategory = null;
        $subCategories = collect();

        if ($request->filled('category')) {
            $selectedCat = Category::with('parent')->find($request->category);

            if ($selectedCat && $rootKnihy) {
                if ($selectedCat->category_id == $rootKnihy->id) {
                    $currentMainCategory = $selectedCat;
                    $subCategories = $selectedCat->children;

                    $ids = $subCategories->pluck('id')->push($selectedCat->id);
                    $query->whereIn('category_id', $ids);
                } else {
                    $currentMainCategory = $selectedCat->parent;
                    $subCategories = $currentMainCategory ? $currentMainCategory->children : collect();

                    $query->where('category_id', $selectedCat->id);
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereRaw("unaccent(name) ILIKE unaccent(?)", ["%{$search}%"])
                  ->orWhereHas('book.authors', function ($authorQuery) use ($search) {
                      $authorQuery->whereRaw(
                          "unaccent(first_name || ' ' || last_name) ILIKE unaccent(?)",
                          ["%{$search}%"]
                      );
                  });
            });
        }

        if ($request->filled('language')) {
            $query->whereHas('book.language', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->language);
            });
        }

        if ($request->filled('publisher')) {
            $query->whereHas('book.publisher', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->publisher);
            });
        }

        if ($request->filled('cover_type')) {
            $query->whereHas('book.binding', function ($q) use ($request) {
                $q->whereIn('name', (array) $request->cover_type);
            });
        }

        if ($request->filled('rating')) {
            $minRating = min((array) $request->rating);

            $query->whereHas('book', function ($q) use ($minRating) {
                $q->where('rating', '>=', $minRating);
            });
        }

        if ($request->has('in_stock')) {
            $query->where('stock_count', '>', 0);
        }

        if ($request->has('bestsellers')) {
            $query->whereHas('book', function ($q) {
                $q->where('is_bestseller', true);
            });
        }

        $sort = $request->get('sort', 'newest');

        switch ($sort) {
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;

            case 'most_expensive':
                $query->orderBy('price', 'desc');
                break;

            case 'newest':
            default:
                $query->join('books', 'products.id', '=', 'books.product_id')
                      ->orderByDesc('books.year')
                      ->select('products.*');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $type = 'book';

        return view('products.index', compact(
            'products',
            'type',
            'mainCategories',
            'subCategories',
            'currentMainCategory'
        ));
    }
}