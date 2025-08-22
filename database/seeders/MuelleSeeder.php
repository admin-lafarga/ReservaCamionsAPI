<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Muelle;

class MuelleSeeder extends Seeder
{
    public function run(): void
    {
        Muelle::insert([
            [
                'empresa_id' => 1,
                'nombre_muelle' => 'Moll A',
                'descripcion' => 'Moll principal',
                'numero' => 1,
                'zona' => 'Nord',
                'abierto_festivos' => true,
                'color' => '#ff0000',
                'estado' => 1,
            ],
            [
                'empresa_id' => 2,
                'nombre_muelle' => 'Moll B',
                'descripcion' => 'Zona sud',
                'numero' => 2,
                'zona' => 'Sud',
                'abierto_festivos' => false,
                'color' => '#00ff00',
                'estado' => 1,
            ],
        ]);
    }
}
