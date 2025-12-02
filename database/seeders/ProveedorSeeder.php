<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Hash;


class ProveedorSeeder extends Seeder {
    public function run(): void {
        Proveedor::insert([
            [
                'entidad_id' => 1,
                'tipo_proveedor_id' => 1,
                'email_notificaciones' => false,
                'email' => 'proveedor1@lafarga.es',
                'contraseña' => Hash::make('123456'),

            ]
        ]);
    }
}
