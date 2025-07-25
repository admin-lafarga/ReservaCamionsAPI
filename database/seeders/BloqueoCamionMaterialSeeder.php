<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloqueoCamionMaterial;

class BloqueoCamionMaterialSeeder extends Seeder {
    public function run(): void {
        BloqueoCamionMaterial::insert([
            [
                'proveedor_id' => 1,
                'material_id' => 1,
                'usuario_id' => 1, //canviar a 1, en el cas que es torni a construir tot de nou.
                'inicio' => now(),
                'fin' => now()->addHours(2),
                'cantidad' => 50
            ]
        ]);
    }
}
