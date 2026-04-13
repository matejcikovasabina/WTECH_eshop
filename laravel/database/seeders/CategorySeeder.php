<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $books = Category::firstOrCreate(
            ['name' => 'Knihy', 'category_id' => null]
        );

        $gifts = Category::firstOrCreate(
            ['name' => 'Darčeky', 'category_id' => null]
        );

        Category::firstOrCreate([
            'name' => 'Fantasy',
            'category_id' => $books->id,
        ]);

        Category::firstOrCreate([
            'name' => 'Detektívky',
            'category_id' => $books->id,
        ]);

        Category::firstOrCreate([
            'name' => 'Romány',
            'category_id' => $books->id,
        ]);

        Category::firstOrCreate([
            'name' => 'Záložky',
            'category_id' => $gifts->id,
        ]);

        Category::firstOrCreate([
            'name' => 'Darčekové karty',
            'category_id' => $gifts->id,
        ]);
    }
}