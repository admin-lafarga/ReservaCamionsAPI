<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder {
    public function run(): void {
        $mat1 = Material::create(
            [
                'codigo_sap' => 'MAT001',
                'nombre' => 'Material 1',
            ],
        );

        $mat2 = Material::create(
            [
                'codigo_sap' => 'MAT002',
                'nombre' => 'Material 2',
            ]
        );

        $mat1->tipo_camiones()->attach(1);
        $mat2->tipo_camiones()->attach(2);
        
        $mat1->muelles()->attach(1);
        $mat1->muelles()->attach(2);

    }
}
