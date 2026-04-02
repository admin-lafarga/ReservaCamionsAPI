<?php

namespace App\Policies;

use App\Models\Estado;
use App\Models\Entidad;
use App\Models\User;

class EstadoPolicy
{
    public function viewAny(User|Entidad $user): bool { return true; }
    public function view(User|Entidad $user, Estado $model): bool { return true; }
    public function create(User|Entidad $user): bool { return $user instanceof User; }
    public function update(User|Entidad $user, Estado $model): bool { return $user instanceof User; }
    public function delete(User|Entidad $user, Estado $model): bool { return $user instanceof User; }
}
