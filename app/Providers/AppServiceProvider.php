<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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

        // Gates Students
        Gate::define('students-show', function ($user): bool {
            return $user->role?->value === 'student';
        });
    }
}
