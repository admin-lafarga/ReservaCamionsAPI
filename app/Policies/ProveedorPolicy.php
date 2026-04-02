<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\Proveedor;
use App\Models\User;

/**
 * Solo usuarios internos pueden gestionar proveedores.
 */
class ProveedorPolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function view(User|Entidad $user, Proveedor $proveedor): bool
    {
        return $user instanceof User;
    }

    public function create(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function update(User|Entidad $user, Proveedor $proveedor): bool
    {
        return $user instanceof User;
    }

    public function delete(User|Entidad $user, Proveedor $proveedor): bool
    {
        return $user instanceof User;
    }
}
