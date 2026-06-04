<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermissionTo('student.view'); }
    public function view(User $user, Student $student): bool { return $user->hasPermissionTo('student.view'); }
    public function create(User $user): bool { return $user->hasPermissionTo('student.create'); }
    public function update(User $user, Student $student): bool { return $user->hasPermissionTo('student.update'); }
    public function delete(User $user, Student $student): bool { return $user->hasPermissionTo('student.delete'); }
}
