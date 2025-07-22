<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transporte;

class TransporteSeeder extends Seeder {
    public function run(): void {
        Transporte::insert([
            [
                'NIF' => '22222222B',
                'PIN' => '2222',
                'abreviatura' => 'TSA',
                'alert' => 0,
                'email' => 'anna@transports.com',
                'estado' => 1,
                'nombre' => 'Transports SA',
                'nombre_contacto' => 'Anna',
                'proveedor_id' => 1,
                'puede_gestionar' => 1,
                'tel1' => '699999999',
                'tel2' => '600000000',
            ],
        ]);
    }
}
