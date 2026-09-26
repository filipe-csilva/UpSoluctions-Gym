<?php

use App\Http\Controllers\Api\V1\FinancialTransactionController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\WorkoutPlanController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth')->group(function (): void {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{student}', [StudentController::class, 'show']);
    Route::get('/financial-transactions', [FinancialTransactionController::class, 'index']);
    Route::get('/workout-plans', [WorkoutPlanController::class, 'index']);
});
