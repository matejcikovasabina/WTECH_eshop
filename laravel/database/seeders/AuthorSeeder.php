<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['first_name' => 'J.R.R.', 'last_name' => 'Tolkien'],
            ['first_name' => 'Joanne', 'last_name' => 'Rowling'],
            ['first_name' => 'Agatha', 'last_name' => 'Christie'],
            ['first_name' => 'George', 'last_name' => 'Orwell'],
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate([
                'first_name' => $author['first_name'],
                'last_name' => $author['last_name'],
            ]);
        }
    }
}