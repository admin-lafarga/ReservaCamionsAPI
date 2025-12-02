<?php

namespace Database\Seeders;

use App\Models\BloqueoGrupoMaterial;
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
            EmpresaLfycsSeeder::class,
            EntidadSeeder::class,
            TipoProveedorSeeder::class,
            ProveedorSeeder::class,
            TipoCamionSeeder::class,
            MuelleSeeder::class,
            MaterialSeeder::class,
            HorarioMuelleSeeder::class,
            TransportistaSeeder::class,
            EstadoSeeder::class,
            RestriccionSeeder::class,
            ReservaSeeder::class,
            ParametroSeeder::class,
            BloqueoGrupoMaterialSeeder::class,
            BloqueoMuelleSeeder::class,
        ]);
    }
}
