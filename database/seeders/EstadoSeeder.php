<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        Estado::insert([
            ['nombre' => 'Pendiente',],
            ['nombre' => 'Finalizada',],
            ['nombre' => 'No asistió',],
            ['nombre' => 'Cancelada',]
        ]);
    }
}
