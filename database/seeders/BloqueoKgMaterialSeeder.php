<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloqueoKgMaterial;

class BloqueoKgMaterialSeeder extends Seeder {
    public function run(): void {
        BloqueoKgMaterial::insert([
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
