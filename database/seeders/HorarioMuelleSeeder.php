<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HorarioMuelle;

class HorarioMuelleSeeder extends Seeder {
    public function run(): void {
        HorarioMuelle::insert([
            [
                'muelle_id' => 1,
                'dia' => 'Dilluns',
                'num_dia' => 1,
                'inicio' => '08:00',
                'fin' => '18:00'
            ]
        ]);
    }
}

