<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:books,product_id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $book = Book::with('product')->findOrFail($validated['product_id']);

        $stock = $book->product?->stock_count ?? 0;
        $quantityToAdd = $validated['quantity'];

        if ($stock <= 0) {
            return back()->with('error', 'Táto kniha momentálne nie je skladom.');
        }

        $cart = session()->get('cart', []);

        $productId = $book->product_id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantityToAdd;

            if ($newQuantity > $stock) {
                return back()->with('error', 'Nie je možné pridať viac kusov, než je na sklade.');
            }

            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            if ($quantityToAdd > $stock) {
                return back()->with('error', 'Nie je možné pridať viac kusov, než je na sklade.');
            }

            $cart[$productId] = [
                'product_id' => $book->product_id,
                'name' => $book->product?->name ?? 'Bez názvu',
                'price' => $book->product?->price ?? 0,
                'cover_image' => $book->cover_image ?? 'adults.webp',
                'quantity' => $quantityToAdd,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Kniha bola pridaná do košíka.');
    }
}