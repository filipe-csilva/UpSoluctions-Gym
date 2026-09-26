<?php

use App\Jobs\SyncStudentStatuses;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SyncStudentStatuses)
    ->dailyAt('00:05')
    ->name('sync-student-statuses');
Schedule::command('notifications:dispatch-operational')
    ->dailyAt('08:00')
    ->timezone('America/Fortaleza')
    ->withoutOverlapping()
    ->name('dispatch-operational-notifications');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
