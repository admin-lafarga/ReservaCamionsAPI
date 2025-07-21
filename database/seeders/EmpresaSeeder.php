<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::insert([
            [
                'nombre' => 'Empresa Exemple 1',
                'descripcion' => 'Descripció de l’empresa 1',
                'estado' => 1,
            ],
            [
                'nombre' => 'Empresa Exemple 2',
                'descripcion' => 'Descripció de l’empresa 2',
                'estado' => 1,
            ],
        ]);
    }
}
