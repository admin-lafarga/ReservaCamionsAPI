<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoProveedor;

class TipoProveedorSeeder extends Seeder {
    public function run(): void {
        TipoProveedor::insert([
            ['nombre' => 'Intern', 'Descripcion' => 'Descripció Intern'],
            ['nombre' => 'Extern', 'Descripcion' => 'Descripció Extern'],
        ]);
    }
}
