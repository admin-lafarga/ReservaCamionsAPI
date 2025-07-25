<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoMuelle;

class TipoMuelleSeeder extends Seeder {
    public function run(): void {
        TipoMuelle::insert([
            ['nombre' => 'Tipus A', 'descripcion' => 'Descripció A', 'materiales_acceptados' => '1,2', 'estado' => 1]
        ]);
    }
}
