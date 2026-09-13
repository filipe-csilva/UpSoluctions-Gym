<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
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

// Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
//     require __DIR__.'/admin.php';
// });

// Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
//     require __DIR__.'/manager.php';
// });

Route::get('/panel', function () {
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('panel');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
