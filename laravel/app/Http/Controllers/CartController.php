<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        if (Auth::check() && empty(session()->get('cart', []))) {
            $cartService->loadDatabaseCartToSession(Auth::user());
        }

        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::with(['images', 'book.authors', 'accessory'])
            ->findOrFail($validated['product_id']);

        $stock = $product->stock_count ?? 0;
        $quantityToAdd = $validated['quantity'];

        if ($stock <= 0) {
            return back()->with('error', 'Tento produkt momentálne nie je skladom.');
        }

        $cart = session()->get('cart', []);
        $productId = $product->id;

        $productInfo = match ($product->type) {
            'book' => $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor',
            'giftcard' => 'Darčeková poukážka',
            'accessory' => 'Knižný doplnok',
            default => 'Produkt',
        };
        $imagePath = $product->images?->first()?->image_path
            ?? $product->accessory?->image_path
            ?? 'images/no-image.webp';

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
                'product_id' => $product->id,
                'name' => $product->name ?? 'Bez názvu',
                'author' => $productInfo,
                'price' => $product->price ?? 0,
                'image_path' => $imagePath,
                'quantity' => $quantityToAdd,
                'stock_count' => $stock,
            ];
        }

        session()->put('cart', $cart);

        if (Auth::check()) {
            $cartService->saveSessionCartForUser(Auth::user(), $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produkt bol pridaný do košíka.');
    }

    public function update(Request $request, CartService $cartService)
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

        if (Auth::check()) {
            $cartService->saveSessionCartForUser(Auth::user(), $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, CartService $cartService)
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

        if (Auth::check()) {
            $cartService->saveSessionCartForUser(Auth::user(), $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Položka bola odstránená z košíka.');
    }
}
