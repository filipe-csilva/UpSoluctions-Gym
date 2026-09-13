<?php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? view('panel')
        : app(AuthenticatedSessionController::class)->create();
})->name('login');

Route::middleware([ 'auth', 'verified', 'role:admin', ])->group(function () { require __DIR__.'/admin.php'; });

Route::get('/panel', function () {
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('panel');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
