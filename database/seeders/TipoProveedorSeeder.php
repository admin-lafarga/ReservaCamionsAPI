<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo_Proveedor;

class TipoProveedorSeeder extends Seeder {
    public function run(): void {
        Tipo_Proveedor::insert([
            ['nombre' => 'Intern'],
            ['nombre' => 'Extern']
        ]);
    }
}
