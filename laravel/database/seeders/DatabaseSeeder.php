<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,

            CategorySeeder::class,
            LanguageSeeder::class,
            PublisherSeeder::class,
            BindingSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            GiftcardSeeder::class,
            AccessorySeeder::class,
        ]);
    }
}