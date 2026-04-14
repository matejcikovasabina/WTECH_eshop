<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::statement('TRUNCATE TABLE categories RESTART IDENTITY CASCADE');

        // ------------- MAINNNNNN KATEGORIA KOREN KNIHY
        $rootKnihy = Category::create(['name' => 'Knihy', 'category_id' => null]);

        // BELETRIA
        $beletria = Category::create(['name' => 'Beletria', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'Detektívky', 'category_id' => $beletria->id]);
        Category::create(['name' => 'Trilery a horory', 'category_id' => $beletria->id]);
        Category::create(['name' => 'Sci-fi a Fantasy', 'category_id' => $beletria->id]);
        Category::create(['name' => 'Romány', 'category_id' => $beletria->id]);

        // NAUCNE
        $naucne = Category::create(['name' => 'Náučné', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'História', 'category_id' => $naucne->id]);
        Category::create(['name' => 'Psychológia', 'category_id' => $naucne->id]);
        Category::create(['name' => 'Populárno-náučná', 'category_id' => $naucne->id]);

        // ZIVOTOPISY
        $zivotopisy = Category::create(['name' => 'Životopisy', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'Skutočné príbehy', 'category_id' => $zivotopisy->id]);
        Category::create(['name' => 'Memoáre', 'category_id' => $zivotopisy->id]);

        // CESTOVATELSKÉ
        $cestovatelske = Category::create(['name' => 'Cestovateľské', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'Sprievodcovia', 'category_id' => $cestovatelske->id]);
        Category::create(['name' => 'Cestopisy', 'category_id' => $cestovatelske->id]);

        // KUCHARSKE
        $kucharske = Category::create(['name' => 'Kuchárske', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'Zdravá výživa', 'category_id' => $kucharske->id]);
        Category::create(['name' => 'Slovenská kuchyňa', 'category_id' => $kucharske->id]);
        Category::create(['name' => 'Pečenie', 'category_id' => $kucharske->id]);

        // UCEBNICE A SLOVNIKY
        $ucebnice = Category::create(['name' => 'Učebnice a slovníky', 'category_id' => $rootKnihy->id]);
        Category::create(['name' => 'Cudzie jazyky', 'category_id' => $ucebnice->id]);
        Category::create(['name' => 'SŠ a VŠ skriptá', 'category_id' => $ucebnice->id]);

        // -------------- DOPLNKY
        $doplnky = Category::create(['name' => 'Doplnky', 'category_id' => null]);
        Category::create(['name' => 'Záložky', 'category_id' => $doplnky->id]);
        Category::create(['name' => 'Lampy na čítanie', 'category_id' => $doplnky->id]);
        Category::create(['name' => 'Obaly na knihy', 'category_id' => $doplnky->id]);

        // -------------- POUKAZKY
        $poukazy = Category::create(['name' => 'Poukážky', 'category_id' => null]);
    }
}