<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'rol_id' => 1, // Administrador
            'nombre' => 'admin',
            'apellidos' => 'Principal',
            'username' => 'admin',
            'email' => 'admin@lafarga.es',
            'contraseña' => Hash::make('123456'),
            'NIF' => '12345678A',
            'tel1' => '123456789',
        ]);

        $user = User::create([
            'rol_id' => 2, // Editor
            'nombre' => 'user',
            'apellidos' => 'Secundario',
            'username' => 'user',
            'email' => 'user@mail.com',
            'contraseña' => Hash::make('123456'),
            'NIF' => '87654321B',
            'tel1' => '987654321',
        ]);

    }
}
