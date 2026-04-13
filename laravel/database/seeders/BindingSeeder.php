<?php

namespace Database\Seeders;

use App\Models\Binding;
use Illuminate\Database\Seeder;

class BindingSeeder extends Seeder
{
    public function run(): void
    {
        $bindings = [
            'Pevná väzba',
            'Brožovaná väzba',
            'E-kniha',
        ];

        foreach ($bindings as $binding) {
            Binding::firstOrCreate([
                'name' => $binding,
            ]);
        }
    }
}