<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\User;

/**
 * Solo usuarios internos (User) pueden gestionar otros usuarios.
 * Las entidades externas (proveedores/transportistas) no tienen acceso.
 */
class UserPolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function view(User|Entidad $user, User $model): bool
    {
        return $user instanceof User;
    }

    public function create(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function update(User|Entidad $user, User $model): bool
    {
        return $user instanceof User;
    }

    public function delete(User|Entidad $user, User $model): bool
    {
        return $user instanceof User;
    }
}
