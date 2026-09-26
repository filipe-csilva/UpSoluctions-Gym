<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\User;
use Carbon\Carbon;

class StudentAccessService
{
    public function syncStudentStatuses(): int
    {
        $updatedCount = 0;
        $users = User::query()
            ->where('role', 'student')
            ->whereHas('studentProfile')
            ->with('studentProfile')
            ->get();

        foreach ($users as $user) {
            $studentId = $user->studentProfile->id;
            $hasActiveEnrollment = Enrollment::query()
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->exists();
            $hasOverduePayment = $hasActiveEnrollment && FinancialTransaction::query()
                ->where('student_id', $studentId)
                ->where('transaction_type', 'income')
                ->whereIn('status', ['pending', 'overdue'])
                ->whereDate('due_date', '<', today())
                ->exists();
            $latestPaidAt = FinancialTransaction::query()
                ->where('student_id', $studentId)
                ->where('transaction_type', 'income')
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->max('paid_at');
            $hasStalePayment = $latestPaidAt !== null && Carbon::parse($latestPaidAt)->lt(now()->subDays(30));
            $shouldBeActive = $hasActiveEnrollment && ! $hasOverduePayment;
            $shouldBeActive = $shouldBeActive && ! $hasStalePayment;

            if ($user->active === $shouldBeActive) {
                continue;
            }

            $user->update(['active' => $shouldBeActive]);
            $updatedCount++;
            ActivityLog::record(
                'updated',
                $user,
                $shouldBeActive
                    ? 'Aluno ativado automaticamente após vinculação de plano.'
                    : ($hasActiveEnrollment
                        ? 'Aluno inativado automaticamente por pendência financeira.'
                        : 'Aluno inativado automaticamente por não possuir plano ativo.'),
            );
        }

        return $updatedCount;
    }

    public function deactivateOverdueStudents(): int
    {
        return $this->syncStudentStatuses();
    }
}
