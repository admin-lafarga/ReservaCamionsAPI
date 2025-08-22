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
            ],
            [
                'codigo_sap' => 'MATULA12',
                'nombre_material' => 'Material test',
                'estado' => 1,
            ]
        ]);
    }
}
