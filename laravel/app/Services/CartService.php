<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\User;

class CartService
{
    public function loadDatabaseCart(User $user): array
    {
        $cart = Cart::with([
            'items.product.images',
            'items.product.book.authors',
        ])->where('user_id', $user->id)->first();

        if (!$cart) {
            return [];
        }

        $sessionCart = [];

        foreach ($cart->items as $item) {
            $product = $item->product;
            $book = $product?->book;

            if (!$product || !$book || $product->stock_count <= 0) {
                continue;
            }

            $sessionCart[$product->id] = $this->formatSessionItem($book, min($item->quantity, $product->stock_count));
        }

        return $sessionCart;
    }

    public function loadDatabaseCartToSession(User $user): array
    {
        $cart = $this->loadDatabaseCart($user);
        session()->put('cart', $cart);

        return $cart;
    }

    public function mergeSessionCartIntoDatabase(User $user): array
    {
        $databaseCart = $this->loadDatabaseCart($user);
        $sessionCart = session()->get('cart', []);
        $mergedCart = $databaseCart;

        foreach ($sessionCart as $productId => $sessionItem) {
            $quantity = (int) ($sessionItem['quantity'] ?? 1);

            if (isset($mergedCart[$productId])) {
                $quantity += (int) ($mergedCart[$productId]['quantity'] ?? 1);
            }

            $book = Book::with(['product.images', 'authors'])
                ->where('product_id', $productId)
                ->first();

            if (!$book || !$book->product || $book->product->stock_count <= 0) {
                continue;
            }

            $mergedCart[$productId] = $this->formatSessionItem(
                $book,
                min($quantity, $book->product->stock_count)
            );
        }

        session()->put('cart', $mergedCart);
        $this->saveSessionCartForUser($user, $mergedCart);

        return $mergedCart;
    }

    public function saveSessionCartForUser(User $user, array $sessionCart): void
    {
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $keptProductIds = [];

        foreach ($sessionCart as $productId => $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            $book = Book::with('product')
                ->where('product_id', $productId)
                ->first();

            if (!$book || !$book->product || $book->product->stock_count <= 0) {
                continue;
            }

            $quantity = min($quantity, $book->product->stock_count);
            $keptProductIds[] = (int) $productId;

            $cart->items()->updateOrCreate(
                ['product_id' => $productId],
                ['quantity' => $quantity]
            );
        }

        $cart->items()
            ->when(!empty($keptProductIds), function ($query) use ($keptProductIds) {
                $query->whereNotIn('product_id', $keptProductIds);
            })
            ->when(empty($keptProductIds), function ($query) {
                $query->whereNotNull('id');
            })
            ->delete();
    }

    public function clearDatabaseCart(User $user): void
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }
    }

    private function formatSessionItem(Book $book, int $quantity): array
    {
        $product = $book->product;
        $authorName = $book->authors->pluck('full_name')->implode(', ') ?: 'Neznámy autor';
        $imagePath = $product?->images?->first()?->image_path ?? 'images/no-image.webp';

        return [
            'product_id' => $book->product_id,
            'name' => $product?->name ?? 'Bez názvu',
            'author' => $authorName,
            'price' => $product?->price ?? 0,
            'image_path' => $imagePath,
            'quantity' => $quantity,
            'stock_count' => $product?->stock_count ?? 0,
        ];
    }
}
