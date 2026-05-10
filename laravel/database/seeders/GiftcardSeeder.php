<?php

namespace Database\Seeders;

use App\Models\Giftcard;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\Category;

// FOTKY BRANE Z: https://www.martinus.sk/

class GiftcardSeeder extends Seeder
{
    public function run(): void
    {

        $category = Category::where('name', 'Darčekové karty')->first();
        $categoryId = $category ? $category->id : 1;
    
        $giftcards = [
            [
                'name' => 'Darčeková karta 10 €',
                'type' => 'giftcard',
                'price' => 10.00,
                'stock_count' => 50,
                'category_id' => $categoryId,
                'value' => 10,
                'expires_at' => Carbon::now()->addMonths(6),
                'description' => 'Darčeková karta s hodnotou 10 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 15 €',
                'type' => 'giftcard',
                'price' => 15.00,
                'stock_count' => 50,
                'category_id' => $categoryId,
                'value' => 15,
                'expires_at' => Carbon::now()->addMonths(6),
                'description' => 'Darčeková karta s hodnotou 15 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 20 €',
                'type' => 'giftcard',
                'price' => 20.00,
                'stock_count' => 75,
                'category_id' => $categoryId,
                'value' => 20,
                'expires_at' => Carbon::now()->addMonths(12),
                'description' => 'Darčeková karta s hodnotou 20 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 25 €',
                'type' => 'giftcard',
                'price' => 25.00,
                'stock_count' => 60,
                'category_id' => $categoryId,
                'value' => 25,
                'expires_at' => Carbon::now()->addMonths(12),
                'description' => 'Darčeková karta s hodnotou 25 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 30 €',
                'type' => 'giftcard',
                'price' => 30.00,
                'stock_count' => 80,
                'category_id' => $categoryId,
                'value' => 30,
                'expires_at' => Carbon::now()->addYear(),
                'description' => 'Darčeková karta s hodnotou 30 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 50 €',
                'type' => 'giftcard',
                'price' => 50.00,
                'stock_count' => 100,
                'category_id' => $categoryId,
                'value' => 50,
                'expires_at' => Carbon::now()->addYear(),
                'description' => 'Darčeková karta s hodnotou 50 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 75 €',
                'type' => 'giftcard',
                'price' => 75.00,
                'stock_count' => 40,
                'category_id' => $categoryId,
                'value' => 75,
                'expires_at' => Carbon::now()->addMonths(18),
                'description' => 'Darčeková karta s hodnotou 75 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
            [
                'name' => 'Darčeková karta 100 €',
                'type' => 'giftcard',
                'price' => 100.00,
                'stock_count' => 30,
                'category_id' => $categoryId,
                'value' => 100,
                'expires_at' => Carbon::now()->addYears(2),
                'description' => 'Darčeková karta s hodnotou 100 €, s ktorou možete nakupovať u nás do expirácie.',
                'images' => ['images/books/giftcard.webp'],
            ],
        ];

        foreach ($giftcards as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'type' => $item['type'],
                'price' => $item['price'],
                'stock_count' => $item['stock_count'],
                'category_id' => $item['category_id'],
                'description' => $item['description'],
            ]);

            Giftcard::create([
                'product_id' => $product->id,
                'value' => $item['value'],
                'code' => $this->generateUniqueCode(),
                'expires_at' => $item['expires_at'],
            ]);

            ProductImage::where('product_id', $product->id)->delete();

            foreach ($item['images'] ?? [] as $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Giftcard::where('code', $code)->exists());

        return $code;
    }
}
