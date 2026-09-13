<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;


Route::middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        
        //Route::resource('units', UnitController::class);
        Route::resource('users', UserController::class);
        //Route::resource('plans', PlanController::class);
        Route::resource('students', StudentController::class);
        Route::get('/dashboard', DashboardController::class)->name('dashboard');


        //Route::get('/students', [StudentController::class, 'index']) ->name('students.index');
        // Route::get('/students/create', [StudentController::class, 'create']) ->name('students.create');
        // Route::post('/students', [StudentController::class, 'store']) ->name('students.store');

    });

// Futuramente:

// Route::resource('units', UnitController::class);
// Route::resource('users', UserController::class);
// Route::resource('students', StudentController::class);
// Route::resource('teachers', TeacherController::class);
// Route::resource('plans', PlanController::class);
// Route::resource('enrollments', EnrollmentController::class);
// Route::resource('exercises', ExerciseController::class);
// Route::resource('workout-plans', WorkoutPlanController::class);
// Route::resource('physical-assessments', PhysicalAssessmentController::class);
// Route::resource('announcements', AnnouncementController::class);