<?php

namespace Database\Seeders;

use App\Models\Entidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entidad::insert([
            [
                'nombre' => 'Proveedor1',
                'abreviatura' => 'P1',
                'nif' => '12345678A',
                'pin' => '1111',
                'nombre_contacto' => 'Contacto1',
                'email' => 'proveedor1@gmail.com',
                'telefono1' => '600000001',
                'alerta' => true,
                'idioma' => 'es',
            ],
            [
                'nombre' => 'Transportista 1', 
                'abreviatura' => 'C1',
                'nif' => '87654321B',
                'pin' => '2222',
                'nombre_contacto' => 'Transportista2',
                'email' => 'transportista1@gmail.com',
                'telefono1' => '600000002',
                'alerta' => false,
                'idioma' => 'es',
        ],
        ]);
    }
}
