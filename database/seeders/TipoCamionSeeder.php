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
                'materiales' => '1,2', // IDs o codis dels materials
                'tiempo_descarga_a' => 15,
                'muelles_permitidos' => '1;2',
                'estado' => 1,
                'bloqueo_muelles' => false,
            ],
            [
                'nombre' => 'Camió Gran',
                'descripcion' => 'Camió amb gran capacitat',
                'materiales' => '3',
                'tiempo_descarga_a' => 30,
                'muelles_permitidos' => '2;3',
                'estado' => 1,
                'bloqueo_muelles' => true,
            ],
        ]);
    }
}

