<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo_Camion;

class TipoCamionSeeder extends Seeder {
    public function run(): void {
        Tipo_Camion::insert([
            [
                'nombre' => 'Camió Petit',
                'descripcion' => 'Camió amb poca capacitat',
                'materiales' => '1,2', // IDs o codis dels materials
                'timpo_descarga_a' => 15,
                'timpo_descarga_b' => 20,
                'muelles_permitidos' => '1,2',
                'estado' => 1,
                'bloqueo_muelles' => false,
            ],
            [
                'nombre' => 'Camió Gran',
                'descripcion' => 'Camió amb gran capacitat',
                'materiales' => '3',
                'timpo_descarga_a' => 30,
                'timpo_descarga_b' => 45,
                'muelles_permitidos' => '2,3',
                'estado' => 1,
                'bloqueo_muelles' => true,
            ],
        ]);
    }
}

