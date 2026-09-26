<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\GeneralSetting;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Policies\AttendancePolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\StudentPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::share('systemSettings', Schema::hasTable('general_settings') ? GeneralSetting::values() : GeneralSetting::defaults());

        Gate::policy(StudentProfile::class, StudentPolicy::class);
        Gate::policy(TeacherProfile::class, TeacherPolicy::class);
        Gate::policy(Enrollment::class, EnrollmentPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('view-dashboard', function ($user): bool {
            return $user->role?->value === 'admin' || $user->role?->value === 'manager';
        });

        Gate::define('view-students', function ($user): bool {
            return $user->role?->value === 'admin' || $user->role?->value === 'manager';
        });

        Gate::define('view-teachers', function ($user): bool {
            return $user->role?->value === 'admin' || $user->role?->value === 'manager';
        });

        Gate::define('view-units', function ($user): bool {
            return $user->role?->value === 'admin';
        });

        Gate::define('view-employees', function ($user): bool {
            return in_array($user->role?->value, ['admin', 'manager'], true);
        });

        Gate::define('view-plans', function ($user): bool {
            return in_array($user->role?->value, ['admin', 'manager'], true);
        });

        Gate::define('view-enrollments', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager'], true));
        Gate::define('view-financial', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager'], true));
        Gate::define('view-student-financial', fn ($user): bool => $user->role?->value === 'student');
        Gate::define('view-attendances', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager'], true));
        Gate::define('view-exercises', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager', 'teacher'], true));
        Gate::define('view-workout-plans', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager', 'teacher'], true));
        Gate::define('view-assessments', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager', 'teacher'], true));
        Gate::define('view-announcements', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager', 'teacher', 'student', 'financial'], true));
        Gate::define('manage-announcements', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager'], true));
        Gate::define('view-messages', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager', 'teacher', 'student', 'financial'], true));
        Gate::define('view-reports', fn ($user): bool => in_array($user->role?->value, ['admin', 'manager'], true));
        Gate::define('manage-users', fn ($user): bool => $user->role?->value === 'admin');
        Gate::define('manage-settings', fn ($user): bool => $user->role?->value === 'admin');

        // Gates Students
        Gate::define('students-show', function ($user): bool {
            return $user->role?->value === 'student';
        });
    }
}
