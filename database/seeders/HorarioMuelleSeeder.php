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
                'dia_semana' => 1,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 2,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 3,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 4,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 5,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 6,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
            [
                'muelle_id' => 1,
                'dia_semana' => 7,
                'inicio' => '08:00',
                'fin' => '18:00'
            ],
        ]);
    }
}

