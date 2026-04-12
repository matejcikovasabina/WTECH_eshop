<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Harry Potter a Kameň mudrcov',
            'author' => 'J.K. Rowling',
            'description' => 'Prvý diel slávnej ságy.',
            'price' => 15.50,
            'genre' => 'Fantasy',
            'language' => 'Slovenčina',
            'stock' => 10,
            'cover_type' => 'Pevná väzba',
            'isbn' => '978-80-00-00000-0',
            'year' => 2000,
            'publisher' => 'Ikar'
        ]);

        Book::create([
            'title' => 'Meno ruže',
            'author' => 'Umberto Eco',
            'description' => 'Historická detektívka.',
            'price' => 12.00,
            'genre' => 'Detektívka',
            'language' => 'Slovenčina',
            'stock' => 5,
            'cover_type' => 'Pevná väzba',
            'isbn' => '978-80-00-00000-0',
            'year' => 2000,
            'publisher' => 'Ikar'
        ]);

        Book::create([
            'title' => 'Mila Debie',
            'author' => 'Neviem',
            'description' => 'Historická detektívka.',
            'price' => 12.00,
            'genre' => 'Detektívka',
            'language' => 'Slovenčina',
            'stock' => 5,
            'cover_type' => 'Pevná väzba',
            'isbn' => '978-80-00-00000-0',
            'year' => 2000,
            'publisher' => 'Ikar'
        ]);
    }
}
