<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Accessory;
use Illuminate\Database\Seeder;
use App\Models\ProductImage; // TOTO PRIDAJ

use App\Models\Category;

class AccessorySeeder extends Seeder
{
    public function run(): void
    {

        $category = Category::where('name', 'Doplnky')->first();
        $categoryId = $category ? $category->id : 1;

        $accessories = [
            [
                'name' => 'Záložka: Tiger',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom tigra.',
                'images' => ['images/books/zalozka_tiger.webp']
            ],
            [
                'name' => 'Záložka: Žirafa',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom žirafy.',
                'images' => ['images/books/zalozka_zirafa.webp']
            ],
            [
                'name' => 'Záložka: Mačka',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom mačky.',
                'images' => ['images/books/zalozka_macka.webp']
            ],
            [
                'name' => 'LED Lampička na čítanie - White',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'images' => [
                    'images/books/lampa_biela.webp', 
                    'images/books/lampa_biela2.webp'
                ]
            ],
            [
                'name' => 'LED Lampička na čítanie - Black',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'images' => [
                    'images/books/lampa_cierna.webp', 
                    'images/books/lampa_cierna2.webp'
                ]
            ],
            [
                'name' => 'Drevený držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový držiak pre vašu poličku.',
                'images' => ['images/books/drziak_na_knihy_dubovy.webp']
            ],
            [
                'name' => 'Biely držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový biely držiak pre vašu poličku.',
                'images' => ['images/books/drziak_na_knihy_biely.webp']
            ],
        ];

        foreach ($accessories as $item) {
            // 1. vytvorenie produktu
            $product = Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'type' => 'accessory',
                    'price' => $item['price'],
                    'stock_count' => rand(5, 20),
                    'category_id' => $categoryId,
                    'description' => $item['description'],
                ]
            );

            // 2. vytvorenie doplnku
            Accessory::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $item['description'],
                ]
            );

            // 3. obrazky
            ProductImage::where('product_id', $product->id)->delete();

            foreach ($item['images'] ?? [] as $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    }
}
