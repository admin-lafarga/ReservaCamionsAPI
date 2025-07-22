<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bloqueo_Kg_Material;

class BloqueoKgMaterialSeeder extends Seeder {
    public function run(): void {
        Bloqueo_Kg_Material::insert([
            [
                'material_id' => 1,
                'usaurio_id' => 1,
                'tipo_proveedor_id' => 1,
                'cantidad' => 100,
                'inicio' => now(),
                'fin' => now()->addHours(2),
            ]
        ]);
    }
}
