<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            'Ikar',
            'Slovart',
            'Tatran',
            'Albatros Media',
        ];

        foreach ($publishers as $publisher) {
            Publisher::firstOrCreate([
                'name' => $publisher,
            ]);
        }
    }
}