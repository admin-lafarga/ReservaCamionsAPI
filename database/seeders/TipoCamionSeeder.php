<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoCamion;

class TipoCamionSeeder extends Seeder {
    public function run(): void {
        TipoCamion::insert([
            [
                'nombre' => 'Camió Petit',
                'descripcion' => 'Camió amb poca capacitat',
                'tiempo_descarga_1' => 15,
                'bloqueo_muelles' => false,
            ],
            [
                'nombre' => 'Camió Gran',
                'descripcion' => 'Camió amb gran capacitat',
                'tiempo_descarga_1' => 30,
                'bloqueo_muelles' => true,
            ],
        ]);
    }
}

