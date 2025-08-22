<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RangoCantidad;

class RangoCantidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RangoCantidad::insert([
            ['min_kg' => 200, 'max_kg' => 30000]
        ]);
    }
}
