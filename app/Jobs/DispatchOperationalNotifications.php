<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Notifications\EnrollmentExpiring;
use App\Notifications\FinancialOverdue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchOperationalNotifications implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        FinancialTransaction::query()->with('student.user')->where('transaction_type', 'income')->whereIn('status', ['pending', 'overdue'])->whereDate('due_date', '<', today())->chunkById(100, function ($transactions): void {
            foreach ($transactions as $transaction) {
                $transaction->student?->user?->notify(new FinancialOverdue($transaction));
            }
        });

        Enrollment::query()->with('student.user')->where('status', 'active')->whereBetween('end_date', [today(), today()->addDays(30)])->chunkById(100, function ($enrollments): void {
            foreach ($enrollments as $enrollment) {
                $enrollment->student?->user?->notify(new EnrollmentExpiring($enrollment));
            }
        });
    }
}
