<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\Transport\MicrosoftGraphTransport;
use App\Services\GraphMailService;

// Models
use App\Models\User;
use App\Models\Reserva;
use App\Models\Proveedor;
use App\Models\Transportista;
use App\Models\Muelle;
use App\Models\Material;
use App\Models\EmpresaLfycs;
use App\Models\Estado;
use App\Models\HorarioMuelle;
use App\Models\BloqueoMuelle;
use App\Models\BloqueoGrupoMaterial;
use App\Models\Restriccion;
use App\Models\Parametro;
use App\Models\Permiso;
use App\Models\Rol;
use App\Models\TipoCamion;
use App\Models\TipoProveedor;
use App\Models\BloqueoGrupoMaterialDetalle;


// Policies
use App\Policies\UserPolicy;
use App\Policies\ReservaPolicy;
use App\Policies\ProveedorPolicy;
use App\Policies\TransportistaPolicy;
use App\Policies\MuellePolicy;
use App\Policies\MaterialPolicy;
use App\Policies\EmpresaLfycsPolicy;
use App\Policies\EstadoPolicy;
use App\Policies\HorarioMuellePolicy;
use App\Policies\BloqueoMuellePolicy;
use App\Policies\BloqueoGrupoMaterialPolicy;
use App\Policies\RestriccionPolicy;
use App\Policies\ParametroPolicy;
use App\Policies\PermisoPolicy;
use App\Policies\RolPolicy;
use App\Policies\TipoCamionPolicy;
use App\Policies\TipoProveedorPolicy;
use App\Policies\BloqueoGrupoMaterialDetallePolicy;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mail::extend('microsoft-graph', function () {
            return new MicrosoftGraphTransport(new GraphMailService());
        });

        // Registro explícito de policies para que Laravel las aplique
        // tanto a User como a Entidad (que usa un guard personalizado)
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Reserva::class, ReservaPolicy::class);
        Gate::policy(Proveedor::class, ProveedorPolicy::class);
        Gate::policy(Transportista::class, TransportistaPolicy::class);
        Gate::policy(Muelle::class, MuellePolicy::class);
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(EmpresaLfycs::class, EmpresaLfycsPolicy::class);
        Gate::policy(Estado::class, EstadoPolicy::class);
        Gate::policy(HorarioMuelle::class, HorarioMuellePolicy::class);
        Gate::policy(BloqueoMuelle::class, BloqueoMuellePolicy::class);
        Gate::policy(BloqueoGrupoMaterial::class, BloqueoGrupoMaterialPolicy::class);
        Gate::policy(BloqueoGrupoMaterialDetalle::class, BloqueoGrupoMaterialDetallePolicy::class);
        Gate::policy(Restriccion::class, RestriccionPolicy::class);
        Gate::policy(Parametro::class, ParametroPolicy::class);
        Gate::policy(Permiso::class, PermisoPolicy::class);
        Gate::policy(Rol::class, RolPolicy::class);
        Gate::policy(TipoCamion::class, TipoCamionPolicy::class);
        Gate::policy(TipoProveedor::class, TipoProveedorPolicy::class);
    }
}
