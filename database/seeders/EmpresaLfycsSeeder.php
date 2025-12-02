<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmpresaLfycs;

class EmpresaLfycsSeeder extends Seeder
{
    public function run(): void
    {
        EmpresaLfycs::insert([
            [
                'nombre' => 'FON1',
                'descripcion' => 'Foneria 1',
            ],
            [
                'nombre' => 'FON2',
                'descripcion' => 'Foneria 2/Rod',
            ],
        ]);
    }
}
