<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Accessory;
use Illuminate\Database\Seeder;

use App\Models\Category;

class AccessorySeeder extends Seeder
{
    public function run(): void
    {

        $category = Category::where('name', 'Doplnky')->first();
        $categoryId = $category ? $category->id : 1;

        $data = [
            [
                'name' => 'Záložka: Nočná obloha',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom hviezd.',
                'image' => 'zalozka_obloha.webp'
            ],
            [
                'name' => 'Záložka: Denna obloha',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom hviezd.',
                'image' => 'zalozka_obloha.webp'
            ],
            [
                'name' => 'Záložka: White',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom hviezd.',
                'image' => 'zalozka_obloha.webp'
            ],
            [
                'name' => 'Záložka: Black',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom hviezd.',
                'image' => 'zalozka_obloha.webp'
            ],
            [
                'name' => 'Záložka: Grey',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom hviezd.',
                'image' => 'zalozka_obloha.webp'
            ],
            [
                'name' => 'LED Lampička na čítanie - White',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'image' => 'lampicka.webp'
            ],
            [
                'name' => 'LED Lampička na čítanie - Black',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'image' => 'lampicka.webp'
            ],
            [
                'name' => 'Drevený držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový držiak pre vašu poličku.',
                'image' => 'drziak.webp'
            ],
            [
                'name' => 'Biely držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový biely držiak pre vašu poličku.',
                'image' => 'drziak.webp'
            ],
        ];

        foreach ($data as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'type' => 'accessory',
                'price' => $item['price'],
                'stock_count' => rand(5, 20),
                'category_id' => $categoryId,
            ]);

            Accessory::create([
                'product_id' => $product->id,
                'description' => $item['description'],
                'image_path' => $item['image'],
            ]);
        }
    }
}
