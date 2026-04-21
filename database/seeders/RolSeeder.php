<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder {
    public function run(): void {
        Rol::insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Accés complet a tots els mòduls'],
            ['nombre' => 'Editor', 'descripcion' => 'Pot accedir a l\'edició dels mòduls'],
            ['nombre' => 'Visor', 'descripcion' => 'Només pot visualitzar la informació dels mòduls'],
        ]);
    }
}
