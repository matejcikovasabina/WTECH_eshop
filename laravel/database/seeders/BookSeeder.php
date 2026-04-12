<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Amerikáni', 'author' => 'Tomáš Hudák', 'price' => 15.50, 'genre' => 'Trilery',
                'description' => 'Historický triler z prostredia slovenskej mafie v USA.', 'language' => 'Slovenčina',
                'stock' => 10, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-551-7890-1', 'year' => 2023, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Meno ruže', 'author' => 'Umberto Eco', 'price' => 12.00, 'genre' => 'Detektívky',
                'description' => 'Klasika svetovej literatúry, historická detektívka.', 'language' => 'Slovenčina',
                'stock' => 5, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-2', 'year' => 2000, 'publisher' => 'Slovart'
            ],
            [
                'title' => 'Mila Debie', 'author' => 'Pavol Rankov', 'price' => 12.00, 'genre' => 'Detektívky',
                'description' => 'Príbeh o hľadaní identity v historickom kontexte.', 'language' => 'Slovenčina',
                'stock' => 8, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-3', 'year' => 2022, 'publisher' => 'Slovart'
            ],
            [
                'title' => 'Padli prvé výstrely', 'author' => 'Juraj Červenák', 'price' => 16.90, 'genre' => 'Detektívky',
                'description' => 'Najnovší prípad kapitána Steinera a notára Barbariča.', 'language' => 'Slovenčina',
                'stock' => 15, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-551-8000-4', 'year' => 2024, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Snehuliak', 'author' => 'Jo Nesbo', 'price' => 14.20, 'genre' => 'Trilery',
                'description' => 'Kultový severský krimi triler s Harrym Holeom.', 'language' => 'Slovenčina',
                'stock' => 20, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-5', 'year' => 2012, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Osvietenie', 'author' => 'Stephen King', 'price' => 13.50, 'genre' => 'Horory',
                'description' => 'Mrazivý horor z opusteného horského hotela.', 'language' => 'Slovenčina',
                'stock' => 7, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-6', 'year' => 2015, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Trhlina', 'author' => 'Jozef Karika', 'price' => 12.90, 'genre' => 'Horory',
                'description' => 'Mysteriózny príbeh z pohoria Tribeč.', 'language' => 'Slovenčina',
                'stock' => 25, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-7', 'year' => 2016, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Da Vinciho kód', 'author' => 'Dan Brown', 'price' => 15.00, 'genre' => 'Trilery',
                'description' => 'Symboly, tajné spolky a napínavé vyšetrovanie.', 'language' => 'Slovenčina',
                'stock' => 12, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-8', 'year' => 2005, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Na smrť', 'author' => 'Jozef Karika', 'price' => 17.50, 'genre' => 'Trilery',
                'description' => 'Epická dráma o dvoch priateľoch v čase nacizmu.', 'language' => 'Slovenčina',
                'stock' => 10, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-9', 'year' => 2012, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Tichý pacient', 'author' => 'Alex Michaelides', 'price' => 11.90, 'genre' => 'Trilery',
                'description' => 'Psychologický triler o žene, ktorá prestala rozprávať.', 'language' => 'Slovenčina',
                'stock' => 18, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-10', 'year' => 2019, 'publisher' => 'Slovart'
            ],
            [
                'title' => 'Hry o život', 'author' => 'Suzanne Collins', 'price' => 12.50, 'genre' => 'Fantasy',
                'description' => 'Dystopický príbeh o prežití v krutej reality show.', 'language' => 'Angličtina',
                'stock' => 30, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-11', 'year' => 2008, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Pán prsteňov', 'author' => 'J.R.R. Tolkien', 'price' => 19.90, 'genre' => 'Fantasy',
                'description' => 'Začiatok legendárneho fantasy dobrodružstva.', 'language' => 'Slovenčina',
                'stock' => 5, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-12', 'year' => 2002, 'publisher' => 'Slovart'
            ],
            [
                'title' => 'Harry Potter a Kameň mudrcov', 'author' => 'J.K. Rowling', 'price' => 14.90, 'genre' => 'Fantasy',
                'description' => 'Chlapec, ktorý prežil, objavuje svet mágie.', 'language' => 'Slovenčina',
                'stock' => 40, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-13', 'year' => 2000, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Zaklínač: Posledné želanie', 'author' => 'Andrzej Sapkowski', 'price' => 13.00, 'genre' => 'Fantasy',
                'description' => 'Poviedky o zaklínačovi Geraltovi z Rivie.', 'language' => 'Čeština',
                'stock' => 14, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-14', 'year' => 2010, 'publisher' => 'Plus'
            ],
            [
                'title' => 'Smäd', 'author' => 'Jo Nesbo', 'price' => 14.50, 'genre' => 'Detektívky',
                'description' => 'Harry Hole čelí vrahovi, ktorý pije krv.', 'language' => 'Slovenčina',
                'stock' => 9, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-15', 'year' => 2017, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Smršť', 'author' => 'Jozef Karika', 'price' => 11.90, 'genre' => 'Horory',
                'description' => 'Prízračný vietor na poľsko-slovenskom pohraničí.', 'language' => 'Slovenčina',
                'stock' => 22, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-16', 'year' => 2020, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'V tieni mafie', 'author' => 'Jozef Karika', 'price' => 12.00, 'genre' => 'Trilery',
                'description' => 'Drsný pohľad na slovenské podsvetie 90-tych rokov.', 'language' => 'Slovenčina',
                'stock' => 11, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-17', 'year' => 2010, 'publisher' => 'Ikar'
            ],
            [
                'title' => 'Chernobyl', 'author' => 'Serhii Plokhy', 'price' => 18.20, 'genre' => 'História',
                'description' => 'Podrobná rekonštrukcia jadrovej katastrofy.', 'language' => 'Angličtina',
                'stock' => 6, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-18', 'year' => 2018, 'publisher' => 'Slovart'
            ],
            [
                'title' => 'Malý princ', 'author' => 'Antoine de Saint-Exupéry', 'price' => 8.90, 'genre' => 'Fantasy',
                'description' => 'Nadčasový príbeh pre deti aj dospelých.', 'language' => 'Slovenčina',
                'stock' => 50, 'cover_type' => 'Pevná väzba', 'isbn' => '978-80-00-00000-19', 'year' => 2014, 'publisher' => 'Slovart'
            ],
            [
                'title' => '1984', 'author' => 'George Orwell', 'price' => 10.50, 'genre' => 'Beletria',
                'description' => 'Vízia totality, kde Veľký brat ťa stále vidí.', 'language' => 'Slovenčina',
                'stock' => 33, 'cover_type' => 'Brožovaná', 'isbn' => '978-80-00-00000-20', 'year' => 2013, 'publisher' => 'Slovart'
            ],
        ];

        // Vytvoríme základnú kategóriu mimo cyklu (stačí raz)
        $defaultCategory = \App\Models\Category::firstOrCreate(['name' => 'Knihy']);

        foreach ($books as $bookData) {
            // 1. Najprv vytvoríme číselníkové údaje (aby sme mali ID-čka)
            $author    = \App\Models\Author::firstOrCreate(['name' => $bookData['author']]);
            $language  = \App\Models\Language::firstOrCreate(['name' => $bookData['language']]);
            $publisher = \App\Models\Publisher::firstOrCreate(['name' => $bookData['publisher']]);
            $coverType = \App\Models\CoverType::firstOrCreate(['name' => $bookData['cover_type']]);

            // 2. Vytvoríme PRODUKT (všeobecné info)
            $product = \App\Models\Product::create([
                'name'        => $bookData['title'], 
                'price'       => $bookData['price'],
                'stock_count' => $bookData['stock'],
                'type'        => 'book',
                'category_id' => $defaultCategory->id,
            ]);

            // 3. Vytvoríme KNIHU (špecifické info) - všimni si premennú $bookData
            $book = \App\Models\Book::create([
                'product_id'    => $product->id,
                'isbn'          => $bookData['isbn'],
                'year'          => $bookData['year'],
                'pages_num' => $bookData['pages_num'] ?? 200,
                'description'   => $bookData['description'],
                'language_id'   => $language->id,
                'publisher_id'  => $publisher->id,
                'cover_type_id' => $coverType->id,
            ]);

            // 4. Prepojíme autora
            $book->authors()->attach($author->id);
        }
    }
}