<?php

namespace App\Policies;

use App\Models\BloqueoMuelle;
use App\Models\Entidad;
use App\Models\User;

class BloqueoMuellePolicy
{
    public function viewAny(User|Entidad $user): bool { return $user instanceof User; }
    public function view(User|Entidad $user, BloqueoMuelle $model): bool { return $user instanceof User; }
    public function create(User|Entidad $user): bool { return $user instanceof User; }
    public function update(User|Entidad $user, BloqueoMuelle $model): bool { return $user instanceof User; }
    public function delete(User|Entidad $user, BloqueoMuelle $model): bool { return $user instanceof User; }
}
