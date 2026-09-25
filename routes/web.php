<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PhysicalAssessmentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WorkoutPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : app(AuthenticatedSessionController::class)->create();
})->name('login');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'can:view-dashboard'])
    ->name('dashboard');

Route::get('/meus-dados', [StudentController::class, 'myData'])
    ->middleware(['auth', 'verified'])
    ->name('students.me');

Route::resource('students', StudentController::class)
    ->middleware(['auth', 'verified', 'can:view-students']);

Route::resource('teachers', TeacherController::class)
    ->middleware(['auth', 'verified', 'can:view-teachers']);

Route::resource('units', UnitController::class)
    ->middleware(['auth', 'verified', 'can:view-units']);

Route::resource('employees', EmployeeController::class)
    ->middleware(['auth', 'verified', 'can:view-employees']);

Route::resource('plans', PlanController::class)
    ->middleware(['auth', 'verified', 'can:view-plans']);

Route::resource('enrollments', EnrollmentController::class)
    ->middleware(['auth', 'verified', 'can:view-enrollments']);

Route::resource('financial', FinancialTransactionController::class)
    ->parameters(['financial' => 'financial'])
    ->middleware(['auth', 'verified', 'can:view-financial']);

Route::resource('attendances', AttendanceController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified', 'can:view-attendances']);

Route::resource('exercises', ExerciseController::class)
    ->middleware(['auth', 'verified', 'can:view-exercises']);

Route::resource('workout-plans', WorkoutPlanController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy'])
    ->middleware(['auth', 'verified', 'can:view-workout-plans']);

Route::resource('assessments', PhysicalAssessmentController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified', 'can:view-assessments']);

Route::resource('announcements', AnnouncementController::class)
    ->middleware(['auth', 'verified', 'can:view-announcements']);

Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.export');
Route::get('/reports/pdf', [ReportController::class, 'pdf'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.pdf');

// Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
//     require __DIR__.'/admin.php';
// });

// Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
//     require __DIR__.'/manager.php';
// });

Route::get('/panel', PanelController::class)
    ->middleware(['auth', 'verified'])
    ->name('panel');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

require __DIR__.'/auth.php';
