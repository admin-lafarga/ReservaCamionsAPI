<?php

namespace App\Policies;

use App\Models\Entidad;
use App\Models\Reserva;
use App\Models\User;

/**
 * - Usuarios internos (User): acceso completo a todas las reservas.
 * - Entidades externas (Entidad): solo pueden ver/crear/actualizar/borrar
 *   reservas donde su entidad_id coincide con el proveedor o transportista.
 */
class ReservaPolicy
{
    public function viewAny(User|Entidad $user): bool
    {
        // Todos los autenticados pueden listar (se filtra en el controller)
        return true;
    }

    public function view(User|Entidad $user, Reserva $reserva): bool
    {
        if ($user instanceof User) return true;

        return $this->perteneceAEntidad($user, $reserva);
    }

    public function create(User|Entidad $user): bool
    {
        // Tanto usuarios internos como proveedores/transportistas pueden crear reservas
        return true;
    }

    public function update(User|Entidad $user, Reserva $reserva): bool
    {
        if ($user instanceof User) return true;

        return $this->perteneceAEntidad($user, $reserva);
    }

    public function delete(User|Entidad $user, Reserva $reserva): bool
    {
        if ($user instanceof User) return true;

        return $this->perteneceAEntidad($user, $reserva);
    }

    /**
     * Comprueba si la entidad es el proveedor o el transportista de la reserva.
     */
    private function perteneceAEntidad(Entidad $entidad, Reserva $reserva): bool
    {
        // Comprobar si es el proveedor
        if ($reserva->proveedor && $reserva->proveedor->entidad_id === $entidad->entidad_id) {
            return true;
        }

        // Comprobar si es el transportista
        if ($reserva->transportista && $reserva->transportista->entidad_id === $entidad->entidad_id) {
            return true;
        }

        return false;
    }
}
