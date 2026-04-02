<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\Muelle;
use App\Models\User;

/**
 * Solo usuarios internos pueden gestionar muelles.
 * Las entidades externas solo pueden consultar (para crear reservas).
 */
class MuellePolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        // Todos pueden leer la lista de muelles (necesario para crear reservas)
        return true;
    }

    public function view(User|Entidad $user, Muelle $muelle): bool
    {
        return true;
    }

    public function create(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function update(User|Entidad $user, Muelle $muelle): bool
    {
        return $user instanceof User;
    }

    public function delete(User|Entidad $user, Muelle $muelle): bool
    {
        return $user instanceof User;
    }
}
