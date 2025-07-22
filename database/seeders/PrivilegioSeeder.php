<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Privilegio;

class PrivilegioSeeder extends Seeder {
    public function run(): void {
        Privilegio::insert([
            [
                'id_tipo_usuario' => 1,
                'view' => true,
                'canEdit' => true,
                'canEditAll' => false,
                'canAdd' => true
            ]
        ]);
    }
}
