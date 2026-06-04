<?php

namespace App\Policies;

use App\Models\Campus;
use App\Models\User;

class CampusPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermissionTo('campus.view'); }
    public function view(User $user, Campus $campus): bool { return $user->hasPermissionTo('campus.view'); }
    public function create(User $user): bool { return $user->hasPermissionTo('campus.create'); }
    public function update(User $user, Campus $campus): bool { return $user->hasPermissionTo('campus.update'); }
    public function delete(User $user, Campus $campus): bool { return $user->hasPermissionTo('campus.delete'); }
}
