<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:books,product_id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $book = Book::with(['product.images', 'authors'])
            ->where('product_id', $validated['product_id'])
            ->firstOrFail();

        $stock = $book->product?->stock_count ?? 0;
        $quantityToAdd = $validated['quantity'];

        if ($stock <= 0) {
            return back()->with('error', 'Táto kniha momentálne nie je skladom.');
        }

        $cart = session()->get('cart', []);
        $productId = $book->product_id;

        $authorName = $book->authors->pluck('full_name')->implode(', ');
        if (empty($authorName)) {
            $authorName = 'Neznámy autor';
        }

        $firstImage = $book->product?->images?->first();
        $imagePath = $firstImage?->image_path ?? 'images/no-image.webp';

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantityToAdd;

            if ($newQuantity > $stock) {
                return back()->with('error', 'Nie je možné pridať viac kusov, než je na sklade.');
            }

            $cart[$productId]['quantity'] = $newQuantity;
            $cart[$productId]['stock_count'] = $stock;
            $cart[$productId]['image_path'] = $imagePath;
        } else {
            if ($quantityToAdd > $stock) {
                return back()->with('error', 'Nie je možné pridať viac kusov, než je na sklade.');
            }

            $cart[$productId] = [
                'product_id' => $book->product_id,
                'name' => $book->product?->name ?? 'Bez názvu',
                'author' => $authorName,
                'price' => $book->product?->price ?? 0,
                'image_path' => $imagePath,
                'quantity' => $quantityToAdd,
                'stock_count' => $stock,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Kniha bola pridaná do košíka.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'action' => ['required', 'in:increase,decrease'],
        ]);

        $cart = session()->get('cart', []);
        $productId = $validated['product_id'];

        if (!isset($cart[$productId])) {
            return redirect()->route('cart.index')->with('error', 'Položka sa v košíku nenašla.');
        }

        if ($validated['action'] === 'increase') {
            if ($cart[$productId]['quantity'] >= $cart[$productId]['stock_count']) {
                return redirect()->route('cart.index')->with('error', 'Nie je možné pridať viac kusov, než je na sklade.');
            }

            $cart[$productId]['quantity']++;
        }

        if ($validated['action'] === 'decrease') {
            $cart[$productId]['quantity']--;

            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = session()->get('cart', []);
        $productId = $validated['product_id'];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Položka bola odstránená z košíka.');
    }
}