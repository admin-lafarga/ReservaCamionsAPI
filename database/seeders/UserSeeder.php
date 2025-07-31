<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'apellidos' => 'Principal',
            'email' => 'a.a@a.es',
            'password' => Hash::make('123456'),
            'PIN' => '1234',
            'NIF' => '12345678A',
            'tel1' => '123456789',
            'rol_id' => 1,
            'estado' => 1,
        ]);
    }
}
