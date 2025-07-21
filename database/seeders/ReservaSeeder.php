<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;

class ReservaSeeder extends Seeder {
    public function run(): void {
        Reserva::insert([
            // [
            //     'tipo_camion_id' => 1,
            //     'tipo_material1_id' => 1,
            //     'tipo_material2_id' => 2,
            //     'proveedor_id' => 1,
            //     'transporte_id' => 1,
            //     'muelle1_id' => 1,
            //     'muelle2_id' => 1,
            //     'status_id' => 1,
            //     'empresa_id' => 1,
            //     'cantidad1' => 100,
            //     'cantidad2' => 50,
            //     'pedido_LF' => 'ORD1234',
            //     'matricula_camion' => '1234ABC',
            //     'inicio1' => now()->toDateTimeString(),
            //     'fin1' => now()->addHour()->toDateTimeString(),
            //     'inicio2' => now()->toDateTimeString(),
            //     'fin2' => now()->addHour()->toDateTimeString(),
            //     'es_aduana' => false,
            //     'notas' => 'Comentaris',
            //     'tel1' => '600000001',
            //     'duracion1' => 60,
            //     'duracion2' => 30,
            // ],
            [
                'tipo_camion_id' => 1,
                'tipo_material1_id' => 2,
                'tipo_material2_id' => 3,
                'proveedor_id' => 1,
                'transporte_id' => 1,
                'muelle1_id' => 2,
                'muelle2_id' => 2,
                'status_id' => 1,
                'empresa_id' => 1,
                'cantidad1' => 80,
                'cantidad2' => 20,
                'pedido_LF' => 'ORD5678',
                'matricula_camion' => '5678DEF',
                'inicio1' => now()->addDay(),
                'fin1' => now()->addDay()->addHour(),
                'inicio2' => now()->addDay(),
                'fin2' => now()->addDay()->addHour(),
                'es_aduana' => true,
                'notas' => 'Entrega internacional',
                'tel1' => '600000002',
                'duracion1' => 45,
                'duracion2' => 20,
            ],
        ]);
    }
}
