<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'Slovenčina',
            'Čeština',
            'English',
            'Deutsch',
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate([
                'name' => $language,
            ]);
        }
    }
}