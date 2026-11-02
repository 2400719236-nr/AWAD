<?php

namespace App\Policies;

use App\Models\CourseRegistration;
use App\Models\User;

class CourseRegistrationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view all registrations') || $user->can('view own registrations');
    }

    public function view(User $user, CourseRegistration $registration): bool
    {
        return $user->can('view all registrations') || $this->owns($user, $registration);
    }

    public function create(User $user): bool
    {
        return $user->can('manage registrations') || $user->can('register for courses');
    }

    /**
     * Admins can drop any registration; students can only drop their own.
     */
    public function delete(User $user, CourseRegistration $registration): bool
    {
        return $user->can('manage registrations')
            || ($user->can('register for courses') && $this->owns($user, $registration));
    }

    private function owns(User $user, CourseRegistration $registration): bool
    {
        return $user->student !== null && $registration->student_id === $user->student->id;
    }
}
