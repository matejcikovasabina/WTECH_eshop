<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['first_name' => 'John', 'last_name' => 'Boyne'],
            ['first_name' => 'J.R.R.', 'last_name' => 'Tolkien'],
            ['first_name' => 'Joanne', 'last_name' => 'Rowling'],
            ['first_name' => 'Agatha', 'last_name' => 'Christie'],
            ['first_name' => 'George', 'last_name' => 'Orwell'],
            ['first_name' => 'Jane', 'last_name' => 'Austen'],
            ['first_name' => 'Dan', 'last_name' => 'Brown'],
            ['first_name' => 'Stephen', 'last_name' => 'King'],
            ['first_name' => 'Antoine', 'last_name' => 'de Saint-Exupéry'],
            ['first_name' => 'Paulo', 'last_name' => 'Coelho'],
            ['first_name' => 'Ernest', 'last_name' => 'Hemingway'],
            ['first_name' => 'Fjodor', 'last_name' => 'Dostojevskij'],
            ['first_name' => 'Victor', 'last_name' => 'Hugo'],
            ['first_name' => 'Bram', 'last_name' => 'Stoker'],
            ['first_name' => 'Mary', 'last_name' => 'Shelley'],
            ['first_name' => 'Arthur Conan', 'last_name' => 'Doyle'],
            ['first_name' => 'Lewis', 'last_name' => 'Carroll'],
            ['first_name' => 'Jules', 'last_name' => 'Verne'],
            ['first_name' => 'Emily', 'last_name' => 'Brontë'],
            ['first_name' => 'Charlotte', 'last_name' => 'Brontë'],
            ['first_name' => 'Harper', 'last_name' => 'Lee'],
            ['first_name' => 'Markus', 'last_name' => 'Zusak'],
            ['first_name' => 'Khaled', 'last_name' => 'Hosseini'],
            ['first_name' => 'John', 'last_name' => 'Green'],
            ['first_name' => 'Suzanne', 'last_name' => 'Collins'],
            ['first_name' => 'Veronica', 'last_name' => 'Roth'],
            ['first_name' => 'C.S.', 'last_name' => 'Lewis'],
            ['first_name' => 'Rick', 'last_name' => 'Riordan'],
            ['first_name' => 'Andrzej', 'last_name' => 'Sapkowski'],
            ['first_name' => 'Umberto', 'last_name' => 'Eco'],
            ['first_name' => 'Miguel', 'last_name' => 'de Cervantes'],
            ['first_name' => 'Vaclav', 'last_name' => 'Smil'],
            ['first_name' => 'Peter', 'last_name' => 'Hlad'],
            ['first_name' => 'Dani', 'last_name' => 'Francis'],
            ['first_name' => 'Angel', 'last_name' => 'Lawson'],
            ['first_name' => 'Lauren', 'last_name' => 'Roberts'],
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate([
                'first_name' => $author['first_name'],
                'last_name' => $author['last_name'],
            ]);
        }
    }
}
