<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restriccion;

class RestriccionSeeder extends Seeder {
    public function run(): void {
        Restriccion::insert([
            ['muelle_id' => 1, 'muelle_restringido_id' => 1],
        ]);
    }
}
