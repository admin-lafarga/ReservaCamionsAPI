<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Informació de prova
        $this->call([
            RolSeeder::class,
            UserSeeder::class,
            EmpresaSeeder::class,
            TipoProveedorSeeder::class,
            ProveedorSeeder::class,
            TipoMaterialSeeder::class,
            TipoCamionSeeder::class,
            TipoMuelleSeeder::class,
            MaterialSeeder::class,
            MuelleSeeder::class,
            HorarioMuelleSeeder::class,
            TransporteSeeder::class,
            StatusSeeder::class,
            RestriccionSeeder::class,
            BloqueoCamionMaterialSeeder::class,
            BloqueoKgMaterialSeeder::class,
            PrivilegioSeeder::class,
            ReservaSeeder::class,
        ]);
    }
}
