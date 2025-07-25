<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoMaterial;

class TipoMaterialSeeder extends Seeder
{
    public function run(): void
    {
        TipoMaterial::insert([
            ['nombre' => 'Material tipus 1', 'tiempoD' => 10],
            ['nombre' => 'Material tipus 2', 'tiempoD' => 15],
            ['nombre' => 'Material tipus 3', 'tiempoD' => 20],
        ]);
    }
}
