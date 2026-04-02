<?php

namespace App\Policies;

use App\Models\TipoCamion;
use App\Models\Entidad;
use App\Models\User;

class TipoCamionPolicy
{
    public function viewAny(User|Entidad $user): bool { return true; }
    public function view(User|Entidad $user, TipoCamion $model): bool { return true; }
    public function create(User|Entidad $user): bool { return $user instanceof User; }
    public function update(User|Entidad $user, TipoCamion $model): bool { return $user instanceof User; }
    public function delete(User|Entidad $user, TipoCamion $model): bool { return $user instanceof User; }
}
