<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\Material;
use App\Models\User;

/**
 * Solo usuarios internos pueden gestionar materiales.
 * Entidades externas pueden leerlos (necesario para crear reservas).
 */
class MaterialPolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        return true;
    }

    public function view(User|Entidad $user, Material $material): bool
    {
        return true;
    }

    public function create(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function update(User|Entidad $user, Material $material): bool
    {
        return $user instanceof User;
    }

    public function delete(User|Entidad $user, Material $material): bool
    {
        return $user instanceof User;
    }
}
