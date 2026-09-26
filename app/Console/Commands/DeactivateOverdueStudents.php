<?php

namespace App\Console\Commands;

use App\Services\StudentAccessService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('students:deactivate-overdue')]
#[Description('Inativa alunos com mensalidades vencidas ou sem pagamento há mais de 30 dias')]
class DeactivateOverdueStudents extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(StudentAccessService $studentAccessService): int
    {
        $count = $studentAccessService->syncStudentStatuses();
        $this->info("{$count} aluno(s) tiveram o status atualizado.");

        return self::SUCCESS;
    }
}
