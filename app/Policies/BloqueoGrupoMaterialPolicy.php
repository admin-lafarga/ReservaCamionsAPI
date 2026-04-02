<?php

namespace App\Policies;

use App\Models\BloqueoGrupoMaterial;
use App\Models\Entidad;
use App\Models\User;

class BloqueoGrupoMaterialPolicy
{
    public function viewAny(User|Entidad $user): bool { return $user instanceof User; }
    public function view(User|Entidad $user, BloqueoGrupoMaterial $model): bool { return $user instanceof User; }
    public function create(User|Entidad $user): bool { return $user instanceof User; }
    public function update(User|Entidad $user, BloqueoGrupoMaterial $model): bool { return $user instanceof User; }
    public function delete(User|Entidad $user, BloqueoGrupoMaterial $model): bool { return $user instanceof User; }
}
