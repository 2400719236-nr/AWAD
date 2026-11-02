<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view courses');
    }

    public function view(User $user, Course $course): bool
    {
        return $user->can('view courses');
    }

    /**
     * Only the admin or the course's own lecturer may see who is registered.
     */
    public function viewStudents(User $user, Course $course): bool
    {
        if ($user->can('view all registrations')) {
            return true;
        }

        return $user->can('view assigned courses')
            && $user->lecturer !== null
            && $course->lecturer_id === $user->lecturer->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create courses');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->can('edit courses');
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->can('delete courses');
    }
}
