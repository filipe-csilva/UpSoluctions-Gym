<?php

namespace App\Policies;

use App\Models\TeacherProfile;
use App\Models\User;

class TeacherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->value, ['admin', 'manager'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeacherProfile $teacherProfile): bool
    {
        return $user->role?->value === 'admin'
            || $user->id === $teacherProfile->user_id
            || in_array((int) $teacherProfile->user?->unit_id, $user->accessibleUnitIds(), true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role?->value, ['admin', 'manager'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeacherProfile $teacherProfile): bool
    {
        return $this->view($user, $teacherProfile) && in_array($user->role?->value, ['admin', 'manager'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeacherProfile $teacherProfile): bool
    {
        return $user->role?->value === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeacherProfile $teacherProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeacherProfile $teacherProfile): bool
    {
        return false;
    }
}
