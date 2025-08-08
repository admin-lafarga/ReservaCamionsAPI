<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;
class ProveedorSeeder extends Seeder {
    public function run(): void {
        Proveedor::insert([
            [
                'codigo_sap' => 'P1234',
                'nombre' => 'Proveïdor 1',
                'abreviatura' => 'P1',
                'NIF' => '12345678Z',
                'PIN' => '1111',
                'nombre_contacto' => 'Joan',
                'email' => 'joan@exemple.com',
                'notificaciones_email' => true,
                'tel1' => '612345678',
                'tel2' => '',
                'alerta' => false,
                'estado' => true,
                'tipo_proveedor_id' => 1,
            ]
        ]);
    }
}
