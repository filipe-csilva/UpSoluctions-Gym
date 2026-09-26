<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\FinancialTransaction;
use App\Models\PhysicalAssessment;
use App\Models\StudentProfile;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->period($request);
        $reportType = $this->reportType($request);
        $unitId = $request->integer('unit_id') ?: null;
        $user = $request->user();
        $unitIds = $user->role?->value === 'manager' ? $user->accessibleUnitIds() : null;
        $financial = $this->financialQuery($from, $to, $unitId, $unitIds);
        $report = $this->reportData($reportType, $from, $to, $unitId, $unitIds);

        return view('reports.index', [
            'units' => $user->role?->value === 'manager' ? $user->managedUnits()->where('active', true)->orderBy('name')->get() : Unit::where('active', true)->orderBy('name')->get(),
            'from' => $from,
            'to' => $to,
            'unitId' => $unitId,
            'reportType' => $reportType,
            'reportHeaders' => $report['headers'],
            'reportRows' => $report['rows'],
            'students' => StudentProfile::query()->whereBetween('created_at', [$from, $to])->when($unitId, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->where('unit_id', $unitId)))->count(),
            'enrollments' => Enrollment::query()->whereBetween('created_at', [$from, $to])->when($unitId, fn ($query) => $query->where('unit_id', $unitId))->count(),
            'attendance' => Attendance::query()->whereBetween('date', [$from->toDateString(), $to->toDateString()])->when($unitId, fn ($query) => $query->where('unit_id', $unitId))->count(),
            'income' => (clone $financial)->where('transaction_type', 'income')->where('status', 'paid')->sum('amount'),
            'expenses' => (clone $financial)->where('transaction_type', 'expense')->where('status', 'paid')->sum('amount'),
            'overdue' => (clone $financial)->whereIn('status', ['overdue', 'pending'])->where(function ($query): void {
                $query->where('status', 'overdue')->orWhereDate('due_date', '<', today());
            })->sum('amount'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->period($request);
        $reportType = $this->reportType($request);
        $unitId = $request->integer('unit_id') ?: null;
        $report = $this->reportData($reportType, $from, $to, $unitId, $request->user()->role?->value === 'manager' ? $request->user()->accessibleUnitIds() : null);
        $title = $this->reportTitle($reportType);
        $unitName = $unitId ? Unit::find($unitId)?->name ?? 'Unidade não encontrada' : 'Todas as unidades';
        $generatedAt = now()->format('d/m/Y H:i');

        return response()->streamDownload(function () use ($report, $title, $from, $to, $unitName, $generatedAt): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [config('app.name', 'GymControl')]);
            fputcsv($handle, [$title]);
            fputcsv($handle, ['Período', $from->format('d/m/Y').' até '.$to->format('d/m/Y')]);
            fputcsv($handle, ['Unidade', $unitName]);
            fputcsv($handle, ['Gerado em', $generatedAt]);
            fputcsv($handle, []);
            fputcsv($handle, $report['headers']);
            foreach ($report['rows'] as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'relatorio-'.$this->reportType($request).'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdf(Request $request): View
    {
        [$from, $to] = $this->period($request);
        $reportType = $this->reportType($request);
        $unitId = $request->integer('unit_id') ?: null;
        $report = $this->reportData($reportType, $from, $to, $unitId, $request->user()->role?->value === 'manager' ? $request->user()->accessibleUnitIds() : null);

        return view('reports.print', [
            'title' => $this->reportTitle($reportType),
            'from' => $from,
            'to' => $to,
            'unitName' => $unitId ? Unit::find($unitId)?->name ?? 'Unidade não encontrada' : 'Todas as unidades',
            'generatedAt' => now(),
            'headers' => $report['headers'],
            'rows' => $report['rows'],
            'companyName' => config('app.name', 'GymControl'),
        ]);
    }

    private function financialQuery(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds)
    {
        return FinancialTransaction::query()
            ->whereBetween('due_date', [$from->toDateString(), $to->toDateString()])
            ->when($unitId, fn ($query) => $query->where('unit_id', $unitId))
            ->when($unitIds !== null, fn ($query) => $query->whereIn('unit_id', $unitIds));
    }

    /** @return array{headers: list<string>, rows: list<list<mixed>>} */
    private function reportData(string $type, Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        return match ($type) {
            'students' => $this->studentReport($from, $to, $unitId, $unitIds),
            'enrollments' => $this->enrollmentReport($from, $to, $unitId, $unitIds),
            'attendance' => $this->attendanceReport($from, $to, $unitId, $unitIds),
            'assessments' => $this->assessmentReport($from, $to, $unitId, $unitIds),
            'cash_flow' => $this->cashFlowReport($from, $to, $unitId, $unitIds),
            'overdue_installments' => $this->overdueInstallmentsReport($from, $to, $unitId, $unitIds),
            default => $this->financialReport($from, $to, $unitId, $unitIds),
        };
    }

    private function financialReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = $this->financialQuery($from, $to, $unitId, $unitIds)->with('student.user')->orderBy('due_date')->get();

        return ['headers' => ['Descrição', 'Aluno', 'Vencimento', 'Valor', 'Tipo', 'Status'], 'rows' => $items->map(fn (FinancialTransaction $item): array => [$item->description, $item->student?->user?->name ?? '-', $item->due_date?->format('d/m/Y') ?? '-', (string) $item->amount, $item->transaction_type, $item->isOverdue() ? 'Em atraso' : match ($item->status) {
            'paid' => 'Pago', 'cancelled' => 'Cancelado', default => 'Pendente'
        }])->all()];
    }

    private function overdueInstallmentsReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = FinancialTransaction::query()
            ->with(['student.user', 'unit', 'enrollment.plan'])
            ->whereIn('status', ['pending', 'overdue'])
            ->whereDate('due_date', '<', today())
            ->whereBetween('due_date', [$from->toDateString(), $to->toDateString()])
            ->when($unitId, fn ($query) => $query->where('unit_id', $unitId))
            ->when($unitIds !== null, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->orderBy('due_date')
            ->get();

        return [
            'headers' => ['Aluno', 'Plano', 'Unidade', 'Vencimento', 'Dias em atraso', 'Valor', 'Status'],
            'rows' => $items->map(fn (FinancialTransaction $item): array => [
                $item->student?->user?->name ?? '-',
                $item->enrollment?->plan?->name ?? '-',
                $item->unit?->name ?? '-',
                $item->due_date?->format('d/m/Y') ?? '-',
                $item->due_date?->diffInDays(today()) ?? 0,
                (string) $item->amount,
                'Em atraso',
            ])->all(),
        ];
    }

    private function cashFlowReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = FinancialTransaction::query()
            ->with(['student.user', 'unit'])
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from, $to])
            ->when($unitId, fn ($query) => $query->where('unit_id', $unitId))
            ->when($unitIds !== null, fn ($query) => $query->whereIn('unit_id', $unitIds))
            ->orderBy('paid_at')
            ->get();

        return [
            'headers' => ['Data', 'Descrição', 'Aluno', 'Unidade', 'Tipo', 'Valor', 'Forma de pagamento'],
            'rows' => $items->map(fn (FinancialTransaction $item): array => [
                $item->paid_at?->format('d/m/Y H:i') ?? '-',
                $item->description,
                $item->student?->user?->name ?? '-',
                $item->unit?->name ?? '-',
                $item->transaction_type === 'income' ? 'Entrada' : 'Saída',
                (string) $item->amount,
                $item->payment_method ?? '-',
            ])->all(),
        ];
    }

    private function studentReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = StudentProfile::query()->with('user.unit')->whereBetween('created_at', [$from, $to])->when($unitId, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->where('unit_id', $unitId)))->when($unitIds !== null, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->whereIn('unit_id', $unitIds)))->latest()->get();

        return ['headers' => ['Nome', 'E-mail', 'Unidade', 'Cadastro'], 'rows' => $items->map(fn (StudentProfile $item): array => [$item->user?->name ?? '-', $item->user?->email ?? '-', $item->user?->unit?->name ?? '-', $item->created_at?->format('d/m/Y') ?? '-'])->all()];
    }

    private function enrollmentReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = Enrollment::query()->with(['student.user', 'plan', 'unit'])->whereBetween('created_at', [$from, $to])->when($unitId, fn ($query) => $query->where('unit_id', $unitId))->when($unitIds !== null, fn ($query) => $query->whereIn('unit_id', $unitIds))->latest()->get();

        return ['headers' => ['Aluno', 'Plano', 'Unidade', 'Início', 'Fim', 'Status'], 'rows' => $items->map(fn (Enrollment $item): array => [$item->student?->user?->name ?? '-', $item->plan?->name ?? '-', $item->unit?->name ?? '-', $item->start_date?->format('d/m/Y') ?? '-', $item->end_date?->format('d/m/Y') ?? '-', ['active' => 'Ativa', 'suspended' => 'Suspensa', 'cancelled' => 'Cancelada', 'expired' => 'Expirada'][$item->status] ?? $item->status])->all()];
    }

    private function attendanceReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = Attendance::query()->with(['student.user', 'unit'])->whereBetween('date', [$from->toDateString(), $to->toDateString()])->when($unitId, fn ($query) => $query->where('unit_id', $unitId))->when($unitIds !== null, fn ($query) => $query->whereIn('unit_id', $unitIds))->latest('date')->get();

        return ['headers' => ['Aluno', 'Unidade', 'Data', 'Entrada', 'Saída'], 'rows' => $items->map(fn (Attendance $item): array => [$item->student?->user?->name ?? '-', $item->unit?->name ?? '-', $item->date?->format('d/m/Y') ?? '-', $item->entry_time ?? '-', $item->exit_time ?? '-'])->all()];
    }

    private function assessmentReport(Carbon $from, Carbon $to, ?int $unitId, ?array $unitIds): array
    {
        $items = PhysicalAssessment::query()->with(['student.user.unit', 'teacher'])->whereBetween('assessment_date', [$from->toDateString(), $to->toDateString()])->when($unitId, fn ($query) => $query->whereHas('student.user', fn ($userQuery) => $userQuery->where('unit_id', $unitId)))->when($unitIds !== null, fn ($query) => $query->whereHas('student.user', fn ($userQuery) => $userQuery->whereIn('unit_id', $unitIds)))->latest('assessment_date')->get();

        return ['headers' => ['Aluno', 'Unidade', 'Data', 'Altura', 'Peso', 'IMC', 'Instrutor'], 'rows' => $items->map(fn (PhysicalAssessment $item): array => [$item->student?->user?->name ?? '-', $item->student?->user?->unit?->name ?? '-', $item->assessment_date?->format('d/m/Y') ?? '-', $item->height.' m', $item->weight.' kg', $item->bmi ?? '-', $item->teacher?->name ?? '-'])->all()];
    }

    private function reportType(Request $request): string
    {
        return in_array($request->string('type')->toString(), ['financial', 'cash_flow', 'overdue_installments', 'students', 'enrollments', 'attendance', 'assessments'], true) ? $request->string('type')->toString() : 'financial';
    }

    private function reportTitle(string $type): string
    {
        if ($type === 'cash_flow') {
            return 'Relatório de fluxo de caixa';
        }

        if ($type === 'overdue_installments') {
            return 'Relatório de parcelas vencidas';
        }

        return ['financial' => 'Relatório financeiro', 'students' => 'Relatório de alunos', 'enrollments' => 'Relatório de matrículas', 'attendance' => 'Relatório de presença', 'assessments' => 'Relatório de avaliações físicas'][$type] ?? 'Relatório';
    }

    /** @return array{0: Carbon, 1: Carbon} */
    private function period(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->string('from')->toString())->startOfDay() : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->string('to')->toString())->endOfDay() : now()->endOfMonth();

        return [$from, $to];
    }
}
