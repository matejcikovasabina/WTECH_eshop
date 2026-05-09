<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

class CartService
{
    public function loadDatabaseCart(User $user): array
    {
        $cart = Cart::with([
            'items.product.images',
            'items.product.book.authors',
            'items.product.accessory',
        ])->where('user_id', $user->id)->first();

        if (!$cart) {
            return [];
        }

        $sessionCart = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            if (!$product || $product->stock_count <= 0) {
                continue;
            }

            $sessionCart[$product->id] = $this->formatSessionItem($product, min($item->quantity, $product->stock_count));
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

            $product = Product::with(['images', 'book.authors', 'accessory'])
                ->find($productId);

            if (!$product || $product->stock_count <= 0) {
                continue;
            }

            $mergedCart[$productId] = $this->formatSessionItem(
                $product,
                min($quantity, $product->stock_count)
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

            $product = Product::find($productId);

            if (!$product || $product->stock_count <= 0) {
                continue;
            }

            $quantity = min($quantity, $product->stock_count);
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

    private function formatSessionItem(Product $product, int $quantity): array
    {
        $productInfo = match ($product->type) {
            'book' => $product->book?->authors?->pluck('full_name')->implode(', ') ?: 'Neznámy autor',
            'giftcard' => 'Darčeková poukážka',
            'accessory' => 'Knižný doplnok',
            default => 'Produkt',
        };
        $imagePath = $product->images?->first()?->image_path
            ?? $product->accessory?->image_path
            ?? 'images/no-image.webp';

        return [
            'product_id' => $product->id,
            'name' => $product->name ?? 'Bez názvu',
            'author' => $productInfo,
            'price' => $product->price ?? 0,
            'image_path' => $imagePath,
            'quantity' => $quantity,
            'stock_count' => $product->stock_count ?? 0,
        ];
    }
}
