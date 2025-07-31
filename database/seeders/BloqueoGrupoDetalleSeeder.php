<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloqueoGrupo;
use App\Models\BloqueoGrupoDetalle;

class BloqueoGrupoDetalleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el grupo de bloqueo
        $bloqueoGrupo = BloqueoGrupo::create([
            'tipo_proveedor_id' => 1,
            'cantidad_total' => 100,
            'cantidad_disponible' => 60,
            'fecha_desde' => now(),
            'fecha_hasta' => now()->addDays(10),
            'usuario_id' => 1,
            'activo' => true
        ]);

        // Agregar detalles al grupo
        BloqueoGrupoDetalle::create([
            'bloqueo_grupo_id' => $bloqueoGrupo->bloqueo_grupo_id,
            'material_id' => 1
        ]);

        BloqueoGrupoDetalle::create([
            'bloqueo_grupo_id' => $bloqueoGrupo->bloqueo_grupo_id,
            'material_id' => 2
        ]);
    }
}
