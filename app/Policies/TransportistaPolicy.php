<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\Transportista;
use App\Models\User;

/**
 * Solo usuarios internos pueden gestionar transportistas.
 */
class TransportistaPolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function view(User|Entidad $user, Transportista $transportista): bool
    {
        return $user instanceof User;
    }

    public function create(User|Entidad $user): bool
    {
        return $user instanceof User;
    }

    public function update(User|Entidad $user, Transportista $transportista): bool
    {
        return $user instanceof User;
    }

    public function delete(User|Entidad $user, Transportista $transportista): bool
    {
        return $user instanceof User;
    }
}
