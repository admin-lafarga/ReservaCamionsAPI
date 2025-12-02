<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transportista;
use Illuminate\Support\Facades\Hash;


class TransportistaSeeder extends Seeder {
    public function run(): void {
        Transportista::insert([
            [
                'entidad_id' => 2,
                'puede_gestionar' => true,
                'email' => 'Entidad2@lafarga.es',
                'contraseña' => Hash::make('123456'),
            ],
        ]);
    }
}
