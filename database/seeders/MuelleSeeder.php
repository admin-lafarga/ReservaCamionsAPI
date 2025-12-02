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
                'empresa_lfycs_id' => 1,
                'nombre' => 'Moll 1',
                'descripcion' => 'Moll principal',
                'color' => '#ff0000',
            ],
            [
                'empresa_lfycs_id' => 1,
                'nombre' => 'Moll 2',
                'descripcion' => 'Zona sud',
                'color' => '#00ff00',
            ],
            [
                'empresa_lfycs_id' => 2,
                'nombre' => 'Moll 3',
                'descripcion' => 'Zona sud',
                'color' => '#00ff',
            ],
            [
                'empresa_lfycs_id' => 2,
                'nombre' => 'Moll 4',
                'descripcion' => 'Zona est',
                'color' => '#0000ff',
            ],
            [
                'empresa_lfycs_id' => 1,
                'nombre' => 'Moll 5',
                'descripcion' => 'Zona oest',
                'color' => '#ffff00',
            ],
        ]);
    }
}
