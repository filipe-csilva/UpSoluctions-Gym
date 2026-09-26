<?php

namespace App\Policies;

use App\Models\StudentProfile;
use App\Models\User;

class StudentPolicy
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
    public function view(User $user, StudentProfile $studentProfile): bool
    {
        return $user->role?->value === 'admin'
            || $user->id === $studentProfile->user_id
            || in_array((int) $studentProfile->user?->unit_id, $user->accessibleUnitIds(), true);
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
    public function update(User $user, StudentProfile $studentProfile): bool
    {
        return $this->view($user, $studentProfile) && in_array($user->role?->value, ['admin', 'manager'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentProfile $studentProfile): bool
    {
        return $user->role?->value === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentProfile $studentProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentProfile $studentProfile): bool
    {
        return false;
    }
}
