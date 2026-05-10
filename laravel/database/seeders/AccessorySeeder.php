<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Accessory;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;

use App\Models\Category;

// FOTKY BRANE Z linkov uvedenych pod jednotlivymi polozkami

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
            // https://www.martinus.sk/
            [
                'name' => 'Záložka: Žirafa',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom žirafy.',
                'images' => ['images/books/zalozka_zirafa.webp']
            ],
            // https://www.martinus.sk/
            [
                'name' => 'Záložka: Mačka',
                'price' => 2.50,
                'description' => 'Ručne maľovaná papierová záložka s motívom mačky.',
                'images' => ['images/books/zalozka_macka.webp']
            ],
            // https://www.martinus.sk/
            [
                'name' => 'LED Lampička na čítanie - White',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'images' => [
                    'images/books/lampa_biela.webp', 
                    'images/books/lampa_biela2.webp'
                ]
            ],
            // https://www.ikea.com/it/it/p/naevlinge-faretto-a-led-con-morsetto-bianco-70449888/?utm_source=google&utm_medium=cpc&utm_campaign=IT_LC_A3_PC_AO_it_PMAX_Generic+Brand_HFBMUL_Decoration_COMBO&gclsrc=aw.ds&gad_source=1&gad_campaignid=20359794189&gbraid=0AAAAADjyGV09BWIGV1ShgRlfRe7PJUyZt&gclid=CjwKCAjwtvvPBhBuEiwAPMijr1S5521wX-POoDGQ_od0NPms1zHHWJE1uA3gO2dmb3CXSY84vY3l7xoCGgoQAvD_BwE
            [
                'name' => 'LED Lampička na čítanie - Black',
                'price' => 12.90,
                'description' => 'Ohybná lampička s tromi úrovňami jasu.',
                'images' => [
                    'images/books/lampa_cierna.webp', 
                    'images/books/lampa_cierna2.webp'
                ]
            ],
            // https://www.ikea.com/it/it/p/naevlinge-faretto-a-led-con-morsetto-nero-00449877/?utm_source=google&utm_medium=cpc&utm_campaign=IT_LC_A3_PC_AO_it_PMAX_Generic+Brand_HFBMUL_Decoration_COMBO&gclsrc=aw.ds&gad_source=1&gad_campaignid=20359794189&gbraid=0AAAAADjyGV09BWIGV1ShgRlfRe7PJUyZt&gclid=CjwKCAjwtvvPBhBuEiwAPMijryztmzj18G3llZ8Gvk_ZdWox3x6cFd86rHAMHCquOrd80XRb1sEzQBoCPWgQAvD_BwE
            [
                'name' => 'Drevený držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový držiak pre vašu poličku.',
                'images' => ['images/books/drziak_na_knihy_dubovy.webp']
            ],
            // https://www.dekoraciedobytu.sk/drziak-na-kucharske-knihy-bamboo-rd3077
            [
                'name' => 'Biely držiak na knihy',
                'price' => 19.00,
                'description' => 'Masívny dubový biely držiak pre vašu poličku.',
                'images' => ['images/books/drziak_na_knihy_biely.webp']
            ],
            // https://allegro.sk/vyhladavanie?string=organizer%20na%20knihy
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
