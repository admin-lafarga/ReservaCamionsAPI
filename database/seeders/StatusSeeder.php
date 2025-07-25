<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder {
    public function run(): void {
        Status::insert([
            [
                'nombre' => 'Pendiente',
                'descripcion' => '',
                'estado' => 1
            ],
            [
                'nombre' => 'Finalizada',
                'descripcion' => '',
                'estado' => 1
            ],
            [
                'nombre' => 'No asistió',
                'descripcion' => '',
                'estado' => 1
            ],
            [
                'nombre' => 'Cancelada',
                'descripcion' => '',
                'estado' => 1
            ]
        ]);
    }
}
