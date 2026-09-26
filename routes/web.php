<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PhysicalAssessmentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : app(AuthenticatedSessionController::class)->create();
})->name('login');

Route::get('/home', HomeController::class)
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'can:view-dashboard'])
    ->name('dashboard');

Route::get('/meus-dados', [StudentController::class, 'myData'])
    ->middleware(['auth', 'verified'])
    ->name('students.me');
Route::get('/minha-matricula', [EnrollmentController::class, 'myHistory'])
    ->middleware(['auth', 'verified', 'can:view-student-enrollment'])
    ->name('student-enrollment.history');
Route::get('/meu-treino', [WorkoutPlanController::class, 'myHistory'])
    ->middleware(['auth', 'verified', 'can:view-student-workout'])
    ->name('student-workout.history');
Route::get('/minha-frequencia', [AttendanceController::class, 'myHistory'])
    ->middleware(['auth', 'verified', 'can:view-student-attendance'])
    ->name('student-attendance.history');

Route::resource('students', StudentController::class)
    ->middleware(['auth', 'verified', 'can:view-students']);

Route::resource('teachers', TeacherController::class)
    ->middleware(['auth', 'verified', 'can:view-teachers']);

Route::resource('units', UnitController::class)
    ->middleware(['auth', 'verified', 'can:view-units']);

Route::resource('employees', EmployeeController::class)
    ->middleware(['auth', 'verified', 'can:view-employees']);

Route::resource('users', UserController::class)
    ->middleware(['auth', 'verified', 'can:manage-users']);
Route::get('/configuracoes', [GeneralSettingsController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:manage-settings'])
    ->name('settings.index');
Route::put('/configuracoes', [GeneralSettingsController::class, 'update'])
    ->middleware(['auth', 'verified', 'can:manage-settings'])
    ->name('settings.update');
Route::get('/configuracoes/theme', [GeneralSettingsController::class, 'theme'])
    ->middleware(['auth', 'verified'])
    ->name('settings.theme');

Route::resource('plans', PlanController::class)
    ->middleware(['auth', 'verified', 'can:view-plans']);

Route::resource('enrollments', EnrollmentController::class)
    ->middleware(['auth', 'verified', 'can:view-enrollments']);
Route::get('/students/{student}/enrollments/history', [EnrollmentController::class, 'history'])
    ->middleware(['auth', 'verified', 'can:view-enrollments'])
    ->name('students.enrollments.history');
Route::get('/teachers/{teacher}/students', [TeacherController::class, 'students'])
    ->middleware(['auth', 'verified'])
    ->name('teachers.students');
Route::get('/meus-alunos', [TeacherController::class, 'myStudents'])
    ->middleware(['auth', 'verified', 'can:view-teacher-students'])
    ->name('teachers.my-students');

Route::get('/financial/cash-flow', [FinancialTransactionController::class, 'cashFlow'])
    ->middleware(['auth', 'verified', 'can:view-financial'])
    ->name('financial.cash-flow');
Route::resource('financial', FinancialTransactionController::class)
    ->parameters(['financial' => 'financial'])
    ->middleware(['auth', 'verified', 'can:view-financial']);
Route::post('/financial/{financial}/mark-paid', [FinancialTransactionController::class, 'markPaid'])
    ->middleware(['auth', 'verified', 'can:view-financial'])
    ->name('financial.mark-paid');
Route::get('/financial/{financial}/receive', [FinancialTransactionController::class, 'receive'])
    ->middleware(['auth', 'verified', 'can:view-financial'])
    ->name('financial.receive');
Route::get('/financial/{financial}/audit', [FinancialTransactionController::class, 'audit'])
    ->middleware(['auth', 'verified', 'can:view-financial'])
    ->name('financial.audit');
Route::post('/financial/{financial}/reverse', [FinancialTransactionController::class, 'reversePayment'])
    ->middleware(['auth', 'verified', 'can:view-financial'])
    ->name('financial.reverse');
Route::get('/meu-financeiro', [FinancialTransactionController::class, 'studentIndex'])
    ->middleware(['auth', 'verified', 'can:view-student-financial'])
    ->name('student-financial.index');
Route::get('/meu-financeiro/{financial}', [FinancialTransactionController::class, 'studentShow'])
    ->middleware(['auth', 'verified', 'can:view-student-financial'])
    ->name('student-financial.show');

Route::resource('attendances', AttendanceController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified', 'can:view-attendances']);
Route::get('/students/{student}/attendances', [AttendanceController::class, 'studentHistory'])
    ->middleware(['auth', 'verified'])
    ->name('attendances.student-history');

Route::get('/meu-treino/presenca', [AttendanceController::class, 'studentCreate'])
    ->middleware(['auth', 'verified'])
    ->name('student-attendance.create');
Route::post('/meu-treino/presenca', [AttendanceController::class, 'studentStore'])
    ->middleware(['auth', 'verified'])
    ->name('student-attendance.store');

Route::resource('exercises', ExerciseController::class)
    ->middleware(['auth', 'verified', 'can:view-exercises']);

Route::resource('workout-plans', WorkoutPlanController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy'])
    ->middleware(['auth', 'verified', 'can:view-workout-plans']);
Route::get('/students/{student}/workout-plans/history', [WorkoutPlanController::class, 'studentHistory'])
    ->middleware(['auth', 'verified'])
    ->name('workout-plans.student-history');

Route::resource('assessments', PhysicalAssessmentController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified', 'can:view-assessments']);
Route::get('/assessments/{student}/history', [PhysicalAssessmentController::class, 'history'])
    ->middleware(['auth', 'verified', 'can:view-assessments'])
    ->name('assessments.history');
Route::get('/assessments/{student}/comparison', [PhysicalAssessmentController::class, 'comparison'])
    ->middleware(['auth', 'verified', 'can:view-assessments'])
    ->name('assessments.comparison');
Route::get('/minhas-avaliacoes', [PhysicalAssessmentController::class, 'myHistory'])
    ->middleware(['auth', 'verified'])
    ->name('assessments.mine');

Route::resource('announcements', AnnouncementController::class)
    ->only(['index', 'show'])
    ->middleware(['auth', 'verified', 'can:view-announcements']);
Route::resource('announcements', AnnouncementController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified', 'can:manage-announcements']);

Route::resource('messages', MessageController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->middleware(['auth', 'verified', 'can:view-messages']);
Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])
    ->middleware(['auth', 'verified', 'can:view-messages'])
    ->name('messages.reply');

Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.export');
Route::get('/reports/pdf', [ReportController::class, 'pdf'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.pdf');
Route::get('/reports/excel', [ReportController::class, 'excel'])
    ->middleware(['auth', 'verified', 'can:view-reports'])
    ->name('reports.excel');

// Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
//     require __DIR__.'/admin.php';
// });

// Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
//     require __DIR__.'/manager.php';
// });

Route::get('/panel', PanelController::class)
    ->middleware(['auth', 'verified'])
    ->name('panel');

Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])
    ->middleware(['auth', 'verified'])
    ->name('notifications.read');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
