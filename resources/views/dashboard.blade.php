@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="gym-dashboard-heading">
        <div>
            <h1>Bem-vindo de volta, {{ auth()->user()->name }}!</h1>
            <p>Aqui está um resumo completo da sua academia.</p>
        </div>
        <div class="gym-dashboard-meta">
            <span class="gym-dashboard-eyebrow">{{ now()->locale('pt_BR')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
            <span class="gym-dashboard-motto">“Disciplina hoje, resultados sempre!” <i class="bi bi-universal-access"></i></span>
        </div>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="gym-dashboard-metrics">
        <div class="gym-metric gym-metric-primary"><i class="bi bi-people-fill"></i><span>Alunos ativos</span><strong>{{ $activeStudents }}</strong><small>Alunos com acesso ativo</small></div>
        <div class="gym-metric gym-metric-danger"><i class="bi bi-person-x-fill"></i><span>Alunos inativos</span><strong>{{ $inactiveStudents }}</strong><small>Alunos sem acesso ativo</small></div>
        <div class="gym-metric gym-metric-success"><i class="bi bi-person-workspace"></i><span>Professores ativos</span><strong>{{ $totalTeachers }}</strong><small>Dados atuais da academia</small></div>
        <div class="gym-metric gym-metric-purple"><i class="bi bi-journal-text"></i><span>Matrículas ativas</span><strong>{{ $activeEnrollments }}</strong><small>Matrículas vigentes</small></div>
        <div class="gym-metric gym-metric-orange"><i class="bi bi-cash-stack"></i><span>Receita do mês</span><strong>R$ {{ number_format((float) $monthlyRevenue, 2, ',', '.') }}</strong><small>Pagamentos recebidos</small></div>
    </div>

    <div class="row gym-dashboard-overview">
        <div class="col-12 col-xl-6">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header">
                    <span class="gym-panel-icon text-primary"><i class="bi bi-bar-chart-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Evolução de alunos</h3><small>Novos alunos cadastrados no período selecionado</small></div>
                    <form method="GET" action="{{ route('dashboard') }}" class="gym-period-filter">
                        <label for="student-period" class="visually-hidden">Período</label>
                        <select id="student-period" name="student_period" onchange="this.form.submit()" aria-label="Período do gráfico">
                            <option value="week" @selected($selectedStudentPeriod === 'week')>Última semana</option>
                            <option value="month" @selected($selectedStudentPeriod === 'month')>Último mês</option>
                            <option value="3m" @selected($selectedStudentPeriod === '3m')>Últimos 3 meses</option>
                            <option value="6m" @selected($selectedStudentPeriod === '6m')>Últimos 6 meses</option>
                            <option value="year" @selected($selectedStudentPeriod === 'year')>Último ano</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    @php
                        $maxEvolution = max(1, $studentEvolution->max('total'));
                        $chartPoints = $studentEvolution->values()->map(function (array $month, int $index) use ($studentEvolution, $maxEvolution): array {
                            $x = $studentEvolution->count() > 1 ? 25 + ($index * (550 / ($studentEvolution->count() - 1))) : 300;
                            $y = 180 - (($month['total'] / $maxEvolution) * 160);

                            return ['x' => $x, 'y' => $y, 'label' => $month['label'], 'total' => $month['total']];
                        });
                    @endphp
                    <div class="gym-evolution-chart" aria-label="Evolução de alunos nos últimos seis meses">
                        <svg viewBox="0 0 600 225" role="img" aria-label="Evolução de alunos nos últimos seis meses">
                            <defs>
                                <linearGradient id="gym-evolution-fill" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#2d8cf0" stop-opacity=".28" />
                                    <stop offset="100%" stop-color="#2d8cf0" stop-opacity=".03" />
                                </linearGradient>
                            </defs>
                            @foreach ([20, 60, 100, 140, 180] as $gridY)
                                <line x1="25" y1="{{ $gridY }}" x2="575" y2="{{ $gridY }}" class="gym-chart-grid-line" />
                                <text x="10" y="{{ $gridY + 4 }}" class="gym-chart-scale">{{ (int) round($maxEvolution * ((180 - $gridY) / 160)) }}</text>
                            @endforeach
                            <polygon points="25,180 {{ $chartPoints->map(fn (array $point): string => $point['x'].','.$point['y'])->implode(' ') }} 575,180" fill="url(#gym-evolution-fill)" />
                            <polyline points="{{ $chartPoints->map(fn (array $point): string => $point['x'].','.$point['y'])->implode(' ') }}" class="gym-chart-line" />
                            @foreach ($chartPoints as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" class="gym-chart-point" />
                                <text x="{{ $point['x'] }}" y="{{ $point['y'] - 12 }}" text-anchor="middle" class="gym-chart-value">{{ $point['total'] }}</text>
                                <text x="{{ $point['x'] }}" y="207" text-anchor="middle" class="gym-chart-label">{{ $point['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header">
                    <span class="gym-panel-icon text-success"><i class="bi bi-pie-chart-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Alunos por unidade</h3><small>Distribuição atual de alunos</small></div>
                </div>
                <div class="card-body">
                    <div class="gym-unit-chart" style="--gym-unit-gradient: {{ $unitGradient }}" aria-label="Distribuição de alunos por unidade">
                        <span>{{ $studentsByUnit->sum('students_count') }}</span>
                    </div>
                    @forelse ($studentsByUnit as $unit)
                        <div class="gym-unit-row">
                            <span class="gym-unit-dot"></span>
                            <span class="flex-grow-1">{{ $unit->name }}</span>
                            <strong>{{ $unit->students_count }}</strong>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhuma unidade ativa cadastrada.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header">
                    <span class="gym-panel-icon text-danger"><i class="bi bi-balloon-heart-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Próximos aniversariantes</h3><small>Alunos da academia</small></div>
                </div>
                <div class="card-body gym-due-list">
                    @forelse ($upcomingBirthdays as $birthday)
                        <div><span>{{ $birthday['date']->format('d/m') }}</span><strong>{{ $birthday['name'] }}</strong><small>{{ $birthday['age'] }} anos</small></div>
                    @empty
                        <p class="text-muted mb-0">Nenhum aniversário cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @php
        $financialMax = max(1, $financialEvolution->flatMap(fn (array $item): array => [$item['revenue'], $item['expenses']])->max());
        $financialPoints = $financialEvolution->values()->map(function (array $item, int $index) use ($financialEvolution, $financialMax): array {
            $x = $financialEvolution->count() > 1 ? 25 + ($index * (550 / ($financialEvolution->count() - 1))) : 300;

            return ['x' => $x, 'revenueY' => 180 - (($item['revenue'] / $financialMax) * 160), 'expensesY' => 180 - (($item['expenses'] / $financialMax) * 160), 'label' => $item['label'], 'revenue' => $item['revenue'], 'expenses' => $item['expenses']];
        });
    @endphp

    <div class="row gym-financial-layout">
        <div class="col-12 col-xl-6">
            <div class="card gym-dashboard-panel gym-financial-evolution mb-4">
        <div class="card-header">
            <span class="gym-panel-icon text-success"><i class="bi bi-graph-up-arrow"></i></span>
            <div class="gym-panel-heading"><h3 class="card-title">Saúde Financeira</h3><small>Receita vs despesas do período selecionado</small></div>
            <form method="GET" action="{{ route('dashboard') }}" class="gym-period-filter">
                <label for="financial-period" class="visually-hidden">Período financeiro</label>
                <select id="financial-period" name="financial_period" onchange="this.form.submit()" aria-label="Período financeiro">
                    <option value="week" @selected($selectedFinancialPeriod === 'week')>Última semana</option>
                    <option value="month" @selected($selectedFinancialPeriod === 'month')>Último mês</option>
                    <option value="3m" @selected($selectedFinancialPeriod === '3m')>Últimos 3 meses</option>
                    <option value="6m" @selected($selectedFinancialPeriod === '6m')>Últimos 6 meses</option>
                    <option value="year" @selected($selectedFinancialPeriod === 'year')>Último ano</option>
                </select>
            </form>
            <div class="gym-chart-legend"><span><i class="gym-legend-revenue"></i> Receita</span><span><i class="gym-legend-expenses"></i> Despesas</span></div>
        </div>
        <div class="card-body">
            <div class="gym-financial-balance">
                <span><i class="bi bi-bullseye"></i> Ponto de equilíbrio: <strong>R$ {{ number_format((float) $financialPeriodExpenses, 2, ',', '.') }}</strong></span>
                <span class="{{ (float) $financialPeriodRevenue >= (float) $financialPeriodExpenses ? 'text-success' : 'text-danger' }}"><i class="bi {{ (float) $financialPeriodRevenue >= (float) $financialPeriodExpenses ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i> {{ (float) $financialPeriodRevenue >= (float) $financialPeriodExpenses ? 'Acima do equilíbrio' : 'Abaixo do equilíbrio' }}</span>
            </div>
            <div class="gym-evolution-chart" aria-label="Comparativo de receitas e despesas">
                <svg viewBox="0 0 600 225" role="img" aria-label="Receita versus despesas">
                    @foreach ([20, 60, 100, 140, 180] as $gridY)
                        <line x1="25" y1="{{ $gridY }}" x2="575" y2="{{ $gridY }}" class="gym-chart-grid-line" />
                    @endforeach
                    <polyline points="{{ $financialPoints->map(fn (array $point): string => $point['x'].','.$point['revenueY'])->implode(' ') }}" class="gym-finance-revenue-line" />
                    <polyline points="{{ $financialPoints->map(fn (array $point): string => $point['x'].','.$point['expensesY'])->implode(' ') }}" class="gym-finance-expenses-line" />
                    @foreach ($financialPoints as $point)
                        <circle cx="{{ $point['x'] }}" cy="{{ $point['revenueY'] }}" r="4" class="gym-finance-revenue-point" />
                        <circle cx="{{ $point['x'] }}" cy="{{ $point['expensesY'] }}" r="4" class="gym-finance-expenses-point" />
                        <text x="{{ $point['x'] }}" y="{{ max(14, $point['revenueY'] - 10) }}" text-anchor="middle" class="gym-finance-revenue-value">R$ {{ number_format((float) $point['revenue'], 2, ',', '.') }}</text>
                        <text x="{{ $point['x'] }}" y="{{ min(197, $point['expensesY'] + 16) }}" text-anchor="middle" class="gym-finance-expenses-value">R$ {{ number_format((float) $point['expenses'], 2, ',', '.') }}</text>
                        <text x="{{ $point['x'] }}" y="207" text-anchor="middle" class="gym-chart-label">{{ $point['label'] }}</text>
                    @endforeach
                </svg>
            </div>
        </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card gym-dashboard-panel gym-financial-side-card gym-financial-expense h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-danger"><i class="bi bi-wallet2"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Próximos vencimentos</h3><small>Contas a pagar</small></div>
                </div>
                <div class="card-body gym-due-list">
                    @forelse ($upcomingPayables as $transaction)
                        <div><span>{{ $transaction->due_date->format('d/m') }}</span><strong>{{ $transaction->description }}</strong><small>R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</small></div>
                    @empty
                        <p class="text-muted mb-0">Nenhuma conta a pagar.</p>
                    @endforelse
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('financial.index', ['transaction_type' => 'expense', 'status' => 'pending', 'from' => today()->format('Y-m-d'), 'to' => today()->addDays(30)->format('Y-m-d')]) }}" class="gym-view-all">Ver no financeiro <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card gym-dashboard-panel gym-financial-side-card gym-financial-revenue h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-success"><i class="bi bi-cash-stack"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Próximos vencimentos</h3><small>Contas a receber</small></div>
                </div>
                <div class="card-body gym-due-list">
                    @forelse ($upcomingReceivables as $transaction)
                        <div><span>{{ $transaction->due_date->format('d/m') }}</span><strong>{{ $transaction->student?->user?->name ?? $transaction->description }}</strong><small>R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</small></div>
                    @empty
                        <p class="text-muted mb-0">Nenhuma conta a receber.</p>
                    @endforelse
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('financial.index', ['transaction_type' => 'income', 'status' => 'pending', 'from' => today()->format('Y-m-d'), 'to' => today()->addDays(30)->format('Y-m-d')]) }}" class="gym-view-all">Ver no financeiro <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-none">
        <div class="col-12 col-xl-4">
            <div class="card gym-dashboard-panel gym-financial-card gym-financial-expense h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-danger"><i class="bi bi-arrow-down-circle-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Despesas</h3><small>Saúde financeira</small></div>
                </div>
                <div class="card-body"><strong>R$ {{ number_format((float) $financialPeriodExpenses, 2, ',', '.') }}</strong><small>Despesas pagas no período</small></div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card gym-dashboard-panel h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-warning"><i class="bi bi-calendar-event"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Próximos vencimentos</h3><small>Mensalidades a acompanhar</small></div>
                </div>
                <div class="card-body gym-due-list">
                    @forelse ($upcomingDueTransactions as $transaction)
                        <div><span>{{ $transaction->due_date->format('d/m') }}</span><strong>{{ $transaction->student->user->name }}</strong><small>R$ {{ number_format((float) $transaction->amount, 2, ',', '.') }}</small></div>
                    @empty
                        <p class="text-muted mb-0">Nenhum vencimento próximo.</p>
                    @endforelse
                </div>
                <div class="card-footer text-end"><small class="text-muted">Próximas cobranças pendentes</small></div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card gym-dashboard-panel gym-financial-card gym-financial-revenue h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-success"><i class="bi bi-arrow-up-circle-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Receita</h3><small>Saúde financeira</small></div>
                </div>
                <div class="card-body"><strong>R$ {{ number_format((float) $financialPeriodRevenue, 2, ',', '.') }}</strong><small>Receitas pagas no período</small></div>
            </div>
        </div>
    </div>

    <div class="row gym-dashboard-lists">
        <div class="col-12 col-xl-3">
            <div class="card h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-success"><i class="bi bi-person-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Novos alunos hoje</h3></div>
                    <a href="{{ route('students.index') }}" class="gym-view-all">Ver todos <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm m-0 small">
                            <thead>
                                <tr><th>Nome</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($todayStudents as $student)
                                    <tr>
                                        <td>{{ $student->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center text-muted">Nenhum aluno cadastrado hoje.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary float-end">Ver todos os alunos</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card h-100 mb-4">
                <div class="card-header">
                    <span class="gym-panel-icon text-primary"><i class="bi bi-person-workspace"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Últimos professores cadastrados</h3></div>
                    <a href="{{ route('teachers.index') }}" class="gym-view-all">Ver todos <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm m-0 small">
                            <thead>
                                <tr><th>Nome</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($latestTeachers as $teacher)
                                    <tr><td>{{ $teacher->user?->name ?? '-' }}</td></tr>
                                @empty
                                    <tr><td class="text-center text-muted">Nenhum instrutor presente.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-primary float-end">Ver todos os instrutores</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card gym-dashboard-panel gym-activities-panel h-100">
        <div class="card-header">
            <span class="gym-panel-icon text-warning"><i class="bi bi-clipboard2-pulse"></i></span>
            <div class="gym-panel-heading"><h3 class="card-title">Atividades recentes</h3><small>Últimas ações registradas no sistema</small></div>
        </div>
        <div class="card-body p-0">
            <div class="gym-activities-list">
                @forelse ($recentActivities as $activity)
                    <div class="gym-activity-item">
                        <span class="gym-activity-icon gym-activity-{{ $activity->dashboardColor() }}"><i class="bi {{ $activity->dashboardIcon() }}"></i></span>
                        <div><strong>{{ $activity->description ?: ucfirst($activity->action) }}</strong><small>{{ $activity->user?->name ?? 'Sistema' }} · {{ $activity->created_at?->format('d/m/Y H:i') }}</small></div>
                    </div>
                @empty
                    <p class="text-muted mb-0 p-3">Nenhuma atividade registrada.</p>
                @endforelse
            </div>
        </div>
            </div>
        </div>
    </div>

    <div class="card gym-dashboard-panel gym-quick-actions">
        <div class="card-header">
            <span class="gym-panel-icon text-warning"><i class="bi bi-lightning-fill"></i></span>
            <div class="gym-panel-heading"><h3 class="card-title">Ações rápidas</h3><small>Acesse as principais funcionalidades do sistema</small></div>
        </div>
        <div class="card-body">
            <div class="gym-actions-grid">
                <a href="{{ route('students.create') }}" class="gym-action gym-action-green"><i class="bi bi-person-plus-fill"></i><span><strong>Novo aluno</strong><small>Cadastrar aluno</small></span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('teachers.index') }}" class="gym-action gym-action-blue"><i class="bi bi-person-workspace"></i><span><strong>Novo professor</strong><small>Acessar instrutores</small></span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('enrollments.create') }}" class="gym-action gym-action-purple"><i class="bi bi-journal-text"></i><span><strong>Nova matrícula</strong><small>Cadastrar matrícula</small></span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('financial.create') }}" class="gym-action gym-action-orange"><i class="bi bi-wallet2"></i><span><strong>Registrar pagamento</strong><small>Lançar receita</small></span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('attendances.create') }}" class="gym-action gym-action-slate"><i class="bi bi-calendar-check"></i><span><strong>Registrar presença</strong><small>Entrada e saída</small></span><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
@stop
