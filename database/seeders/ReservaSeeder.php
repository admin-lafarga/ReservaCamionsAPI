<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;

class ReservaSeeder extends Seeder {
    public function run(): void {
        Reserva::insert([
            [
                'empresa_lfycs_id' => 1,
                'tipo_camion_id' => 1,
                'material1_id' => 1,
                'proveedor_id' => 1,
                'transportista_id' => 1,
                'muelle_id' => 1,
                'estado_id' => 1,
                'cantidad1' => 100,
                'pedido1' => 'ORD1234',
                'matricula_camion' => '1234ABC',
                'inicio' => now()->toDateTimeString(),
                'fin' => now()->addHour()->toDateTimeString(),
                'aduana' => false,
                'telefono' => '600000001',
                'duracion' => 60,
            ],
            [
                'empresa_lfycs_id' => 2,
                'tipo_camion_id' => 1,
                'material_id' => 1,
                'proveedor_id' => 1,
                'transportista_id' => 1,
                'muelle_id' => 4,
                'estado_id' => 1,
                'cantidad1' => 80,
                'pedido1' => 'ORD5678',
                'matricula_camion' => '5678DEF',
                'inicio' => now()->addDay(),
                'fin' => now()->addDay(2)->addHour(),
                'aduana' => true,
                'telefono' => '600000002',
                'duracion' => 45,
            ],
        ]);
    }
}
