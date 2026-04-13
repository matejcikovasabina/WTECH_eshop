<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Binding;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $fantasyCategory = Category::where('name', 'Fantasy')->firstOrFail();
        $detectiveCategory = Category::where('name', 'Detektívky')->firstOrFail();

        $slovakLanguage = Language::where('name', 'Slovenčina')->firstOrFail();
        $englishLanguage = Language::where('name', 'English')->firstOrFail();

        $ikarPublisher = Publisher::where('name', 'Ikar')->firstOrFail();
        $slovartPublisher = Publisher::where('name', 'Slovart')->firstOrFail();

        $hardBinding = Binding::where('name', 'Pevná väzba')->firstOrFail();
        $paperBinding = Binding::where('name', 'Brožovaná väzba')->firstOrFail();

        $tolkien = Author::where('first_name', 'J.R.R.')->where('last_name', 'Tolkien')->firstOrFail();
        $christie = Author::where('first_name', 'Agatha')->where('last_name', 'Christie')->firstOrFail();
        $orwell = Author::where('first_name', 'George')->where('last_name', 'Orwell')->firstOrFail();

        // 1. Hobbit
        $hobbitProduct = Product::updateOrCreate(
            ['name' => 'Hobbit'],
            [
                'type' => 'book',
                'price' => 14.90,
                'stock_count' => 25,
                'category_id' => $fantasyCategory->id,
            ]
        );

        $hobbitBook = Book::updateOrCreate(
            ['product_id' => $hobbitProduct->id],
            [
                'isbn' => '9788055601234',
                'publisher_id' => $ikarPublisher->id,
                'language_id' => $slovakLanguage->id,
                'binding_id' => $hardBinding->id,
                'year' => 2020,
                'pages_num' => 304,
                'weight' => 0.45,
                'width' => 14.00,
                'height' => 21.00,
                'depth' => 2.50,
            ]
        );

        $hobbitBook->authors()->syncWithoutDetaching([$tolkien->id]);

        // 2. Vražda v Orient exprese
        $orientProduct = Product::updateOrCreate(
            ['name' => 'Vražda v Orient exprese'],
            [
                'type' => 'book',
                'price' => 12.50,
                'stock_count' => 18,
                'category_id' => $detectiveCategory->id,
            ]
        );

        $orientBook = Book::updateOrCreate(
            ['product_id' => $orientProduct->id],
            [
                'isbn' => '9788022205678',
                'publisher_id' => $slovartPublisher->id,
                'language_id' => $slovakLanguage->id,
                'binding_id' => $paperBinding->id,
                'year' => 2019,
                'pages_num' => 256,
                'weight' => 0.32,
                'width' => 13.50,
                'height' => 20.00,
                'depth' => 2.00,
            ]
        );

        $orientBook->authors()->syncWithoutDetaching([$christie->id]);

        // 3. 1984
        $book1984Product = Product::updateOrCreate(
            ['name' => '1984'],
            [
                'type' => 'book',
                'price' => 11.90,
                'stock_count' => 30,
                'category_id' => $detectiveCategory->id,
            ]
        );

        $book1984 = Book::updateOrCreate(
            ['product_id' => $book1984Product->id],
            [
                'isbn' => '9780451524935',
                'publisher_id' => $slovartPublisher->id,
                'language_id' => $englishLanguage->id,
                'binding_id' => $paperBinding->id,
                'year' => 2015,
                'pages_num' => 328,
                'weight' => 0.29,
                'width' => 12.90,
                'height' => 19.80,
                'depth' => 2.10,
            ]
        );

        $book1984->authors()->syncWithoutDetaching([$orwell->id]);
    }
}