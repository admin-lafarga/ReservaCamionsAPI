<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder {
    public function run(): void {
        Rol::insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Accés complet', 'estado' => 1],
            ['nombre' => 'Usuari', 'descripcion' => 'Accés limitat', 'estado' => 1],
        ]);
    }
}
