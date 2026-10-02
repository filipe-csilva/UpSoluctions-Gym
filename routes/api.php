<?php

use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\FinancialTransactionController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\WorkoutPlanController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::middleware('auth.jwt')->group(function (): void {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/students', [StudentController::class, 'index']);
        Route::get('/students/{student}', [StudentController::class, 'show']);
        Route::get('/units', [CatalogController::class, 'units']);
        Route::get('/plans', [CatalogController::class, 'plans']);
        Route::get('/enrollments', [CatalogController::class, 'enrollments']);
        Route::get('/attendances', [CatalogController::class, 'attendances']);
        Route::get('/assessments', [CatalogController::class, 'assessments']);
        Route::get('/exercises', [CatalogController::class, 'exercises']);
        Route::get('/users', [CatalogController::class, 'users']);
        Route::get('/financial-transactions', [FinancialTransactionController::class, 'index']);
        Route::get('/workout-plans', [WorkoutPlanController::class, 'index']);
        Route::get('/announcements', [AnnouncementController::class, 'index']);
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
        Route::get('/messages', [MessageController::class, 'index']);
        Route::get('/messages/{message}', [MessageController::class, 'show']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'read']);
        Route::get('/reports/summary', [ReportController::class, 'summary']);
    });
});
