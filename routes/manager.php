<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:manager'])
    ->group(function () {

        Route::resource('students', StudentController::class);
    });
