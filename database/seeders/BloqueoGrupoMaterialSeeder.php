<?php

namespace Database\Seeders;

use App\Models\BloqueoGrupoMaterial;
use App\Models\BloqueoGrupoMaterialDetalle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloqueoGrupoMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloqueosData = [
            [
                'bloqueo_grupo_id' => 1,
                'tipo_proveedor_id' => 1,
                'cantidad_total' => 1000,
                'cantidad_disponible' => 1000,
                'inicio' => now(),
                'fin' => now()->addDays(30),
            ],
            [
                'bloqueo_grupo_id' => 2,
                'tipo_proveedor_id' => 1,
                'cantidad_total' => 500,
                'cantidad_disponible' => 500,
                'inicio' => now(),
                'fin' => now()->addDays(15),
            ],
            [
                'bloqueo_grupo_id' => 3,
                'tipo_proveedor_id' => 2,
                'cantidad_total' => 200,
                'cantidad_disponible' => 200,
                'inicio' => now(),
                'fin' => now()->addDays(45),
            ],
        ];

        foreach ($bloqueosData as $data) {
            $bloqueo = BloqueoGrupoMaterial::create($data);

            $bloqueo->detalles()->createMany([
                ['material_id' => 1],
                ['material_id' => 2],
            ]);
        }
    
    }
}
