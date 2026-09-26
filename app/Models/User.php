<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'unit_id', 'role', 'active', 'avatar_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<UserFactory> */
    use CanResetPassword, HasFactory, Notifiable, SoftDeletes;

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->avatar_path ? asset('storage/'.$this->avatar_path) : '';
    }

    public function profileAvatarInitials(): string
    {
        $names = preg_split('/\s+/', trim($this->name)) ?: [];
        $initials = collect(array_slice($names, 0, 2))
            ->map(static fn (string $name): string => mb_substr($name, 0, 1))
            ->implode('');

        return mb_strtoupper($initials ?: mb_substr($this->name, 0, 2));
    }

    public function profileAvatarGenderClass(): string
    {
        $gender = mb_strtolower((string) $this->studentProfile?->gender);

        return match (true) {
            in_array($gender, ['m', 'masculino', 'male'], true) => 'profile-avatar-male',
            in_array($gender, ['f', 'feminino', 'female'], true) => 'profile-avatar-female',
            default => 'profile-avatar-other',
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'active' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function employeeProfile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function workoutPlansAsTeacher(): HasMany
    {
        return $this->hasMany(WorkoutPlan::class, 'teacher_id');
    }

    public function managedUnits(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'manager_unit');
    }

    /**
     * @return list<int>
     */
    public function accessibleUnitIds(): array
    {
        return $this->managedUnits()
            ->pluck('units.id')
            ->push($this->unit_id)
            ->filter()
            ->map(static fn ($unitId): int => (int) $unitId)
            ->unique()
            ->values()
            ->all();
    }
}
