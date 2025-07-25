<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder {
    public function run(): void {
        Material::insert([
            [
                'codigo_sap' => 'MAT001',
                'nombre_material' => 'Material Demo',
                'estado' => 1,
                'camiones_permitidos' => '1',
                'muelles_permitidos' => '1',
                'max_concurrencia' => 5
            ],
            [
                'codigo_sap' => 'MATULA12',
                'nombre_material' => 'Material test',
                'estado' => 1,
                'camiones_permitidos' => '1;2',
                'muelles_permitidos' => '1;2',
                'max_concurrencia' => 5
            ]
        ]);
    }
}
