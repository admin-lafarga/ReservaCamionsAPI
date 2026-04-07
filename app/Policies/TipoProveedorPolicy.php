<?php

namespace App\Policies;

use App\Models\TipoProveedor;
use App\Models\Entidad;
use App\Models\User;

class TipoProveedorPolicy
{
    public function viewAny(User|Entidad $user): bool { return true; }
    public function view(User|Entidad $user, TipoProveedor $model): bool { return true; }
    public function create(User|Entidad $user): bool { return $user instanceof User; }
    public function update(User|Entidad $user, TipoProveedor $model): bool { return $user instanceof User; }
    public function delete(User|Entidad $user, TipoProveedor $model): bool { return $user instanceof User; }
}
