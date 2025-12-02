<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parametro;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parametro::insert([
            [
                'clave' => 'min_kg',
                'valor' => 200,
            ],
            [
                'clave' => 'max_kg',
                'valor' => 2000,
            ]
        ]);
    }
}
