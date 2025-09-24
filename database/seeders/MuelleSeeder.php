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
            [
                'empresa_id' => 3,
                'nombre_muelle' => 'Moll B',
                'descripcion' => 'Zona sud',
                'numero' => 3,
                'zona' => 'Sud',
                'abierto_festivos' => false,
                'color' => '#00ff',
                'estado' => 1,
            ],
            [
                'empresa_id' => 4,
                'nombre_muelle' => 'Moll C',
                'descripcion' => 'Zona est',
                'numero' => 4,
                'zona' => 'Est',
                'abierto_festivos' => true,
                'color' => '#0000ff',
                'estado' => 1,
            ],
            [
                'empresa_id' => 5,
                'nombre_muelle' => 'Moll D',
                'descripcion' => 'Zona oest',
                'numero' => 5,
                'zona' => 'Oest',
                'abierto_festivos' => true,
                'color' => '#ffff00',
                'estado' => 1,
            ],
        ]);
    }
}
