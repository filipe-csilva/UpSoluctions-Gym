<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? view('panel')
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

// Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
//     require __DIR__.'/admin.php';
// });

// Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
//     require __DIR__.'/manager.php';
// });

Route::get('/panel', function () {
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('panel');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

require __DIR__.'/auth.php';
