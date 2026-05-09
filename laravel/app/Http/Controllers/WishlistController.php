<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Auth::user()
            ->wishlistProducts()
            ->with(['images', 'book.authors', 'accessory'])
            ->get();

        return view('wishlist.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        Auth::user()->wishlistProducts()->syncWithoutDetaching([
            $validated['product_id'],
        ]);

        return back()->with('success', 'Produkt bol pridaný do wishlistu.');
    }

    public function destroy(Product $product)
    {
        Auth::user()->wishlistProducts()->detach($product->id);

        return back()->with('success', 'Produkt bol odstránený z wishlistu.');
    }
}
