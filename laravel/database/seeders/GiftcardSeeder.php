<?php

namespace Database\Seeders;

use App\Models\Giftcard;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GiftcardSeeder extends Seeder
{
    public function run(): void
    {
        $giftcards = [
            [
                'name' => 'Darčeková karta 10 €',
                'type' => 'giftcard',
                'price' => 10.00,
                'stock_count' => 50,
                'category_id' => null,
                'value' => 10,
                'expires_at' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'Darčeková karta 15 €',
                'type' => 'giftcard',
                'price' => 15.00,
                'stock_count' => 50,
                'category_id' => null,
                'value' => 15,
                'expires_at' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'Darčeková karta 20 €',
                'type' => 'giftcard',
                'price' => 20.00,
                'stock_count' => 75,
                'category_id' => null,
                'value' => 20,
                'expires_at' => Carbon::now()->addMonths(12),
            ],
            [
                'name' => 'Darčeková karta 25 €',
                'type' => 'giftcard',
                'price' => 25.00,
                'stock_count' => 60,
                'category_id' => null,
                'value' => 25,
                'expires_at' => Carbon::now()->addMonths(12),
            ],
            [
                'name' => 'Darčeková karta 30 €',
                'type' => 'giftcard',
                'price' => 30.00,
                'stock_count' => 80,
                'category_id' => null,
                'value' => 30,
                'expires_at' => Carbon::now()->addYear(),
            ],
            [
                'name' => 'Darčeková karta 50 €',
                'type' => 'giftcard',
                'price' => 50.00,
                'stock_count' => 100,
                'category_id' => null,
                'value' => 50,
                'expires_at' => Carbon::now()->addYear(),
            ],
            [
                'name' => 'Darčeková karta 75 €',
                'type' => 'giftcard',
                'price' => 75.00,
                'stock_count' => 40,
                'category_id' => null,
                'value' => 75,
                'expires_at' => Carbon::now()->addMonths(18),
            ],
            [
                'name' => 'Darčeková karta 100 €',
                'type' => 'giftcard',
                'price' => 100.00,
                'stock_count' => 30,
                'category_id' => null,
                'value' => 100,
                'expires_at' => Carbon::now()->addYears(2),
            ],
        ];

        foreach ($giftcards as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'type' => $item['type'],
                'price' => $item['price'],
                'stock_count' => $item['stock_count'],
                'category_id' => $item['category_id'],
            ]);

            Giftcard::create([
                'product_id' => $product->id,
                'value' => $item['value'],
                'code' => $this->generateUniqueCode(),
                'expires_at' => $item['expires_at'],
            ]);
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