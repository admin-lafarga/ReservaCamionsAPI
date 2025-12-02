<?php

namespace Database\Seeders;

use App\Models\BloqueoMuelle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloqueoMuelleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BloqueoMuelle::insert([
            [
                'muelle_id' => 1,
                'asunto' => 'Mantenimiento programado',
                'inicio' => now(),
                'fin' => now()->addDays(7),
            ],
            [
                'muelle_id' => 2,
                'asunto' => 'Reparaciones urgentes',
                'inicio' => now()->addDays(1),
                'fin' => now()->addDays(3),
            ],
            [
                'muelle_id' => 3,
                'asunto' => 'Inspección anual',
                'inicio' => now()->addDays(5),
                'fin' => now()->addDays(10),
            ],
        ]);
    }
}
