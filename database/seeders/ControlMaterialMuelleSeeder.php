<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ControlMaterialMuelle;

class ControlMaterialMuelleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ControlMaterialMuelle::insert([
            [
                'material_id' => 1,
                'tipo_camion_id' => 1,
                'muelle_id' => 1,
            ],
            [
                'material_id' => 1,
                'tipo_camion_id' => 1,
                'muelle_id' => 2,
            ],
            [
                'material_id' => 1,
                'tipo_camion_id' => 2,
                'muelle_id' => 1,
            ],
            [
                'material_id' => 1,
                'tipo_camion_id' => 2,
                'muelle_id' => 2,
            ],
            [
                'material_id' => 2,
                'tipo_camion_id' => 1,
                'muelle_id' => 1,
            ],
            [
                'material_id' => 2,
                'tipo_camion_id' => 2,
                'muelle_id' => 1,
            ],
        ]);
    }
}
