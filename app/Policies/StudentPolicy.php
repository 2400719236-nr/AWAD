<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view students');
    }

    /**
     * Admins can view any student. A student can only view their own record.
     * A lecturer can view students registered in one of their courses.
     */
    public function view(User $user, Student $student): bool
    {
        if ($user->can('view students')) {
            return true;
        }

        if ($user->student && $user->student->is($student)) {
            return true;
        }

        return $user->lecturer !== null
            && $student->courses()->where('lecturer_id', $user->lecturer->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('create students');
    }

    public function update(User $user, Student $student): bool
    {
        return $user->can('edit students');
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->can('delete students');
    }
}
