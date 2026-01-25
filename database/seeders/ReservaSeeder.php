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
                'material1_id' => 1,
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

        // Generar reservas aleatorias para llenar el calendario
        $reservas = [];
        $now = now();
        
        for ($i = 0; $i < 50; $i++) {
            $diaOffset = rand(0, 14); // Próximos 14 días
            $horaInicio = rand(6, 18); // Entre 6:00 y 18:00
            $duracion = [30, 60, 90][rand(0, 2)];
            
            $inicio = $now->copy()->addDays($diaOffset)->setHour($horaInicio)->setMinute(0)->setSecond(0);
            $fin = $inicio->copy()->addMinutes($duracion);
            
            $reservas[] = [
                'empresa_lfycs_id' => rand(1, 2),
                'tipo_camion_id' => rand(1, 2),
                'material1_id' => rand(1, 2),
                'proveedor_id' => 1,
                'transportista_id' => 1,
                'muelle_id' => rand(1, 5),
                'estado_id' => 1, // Solicitada
                'cantidad1' => rand(10, 100),
                'pedido1' => 'PED-' . rand(1000, 9999),
                'matricula_camion' => rand(1000, 9999) . ['ABC','DEF','GHI'][rand(0,2)],
                'inicio' => $inicio->toDateTimeString(),
                'fin' => $fin->toDateTimeString(),
                'aduana' => (bool)rand(0, 1),
                'telefono' => '600000' . sprintf('%03d', $i),
                'duracion' => $duracion,
            ];
        }
        
        Reserva::insert($reservas);
    }
}
