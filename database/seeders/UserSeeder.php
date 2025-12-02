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
            'nombre' => 'admin',
            'apellidos' => 'Principal',
            'email' => 'admin@lafarga.es',
            'contraseña' => Hash::make('123456'),
            'NIF' => '12345678A',
            'tel1' => '123456789',
        ]);

        $user = User::create([
            'nombre' => 'user',
            'apellidos' => 'Secundario',
            'email' => 'user@mail.com',
            'contraseña' => Hash::make('123456'),
            'NIF' => '87654321B',
            'tel1' => '987654321',
        ]);

        $admin->roles()->attach(1); // Asignar rol de administrador
        $user->roles()->attach(2); // Asignar rol de administrador

    }
}
