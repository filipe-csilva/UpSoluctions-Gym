@extends('adminlte::page')

@section('title', 'Dashboard')

@push('js')
    <script>
        window.history.replaceState({}, document.title, '/');
    </script>
@endpush

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
        <div class="gym-metric gym-metric-primary"><i class="bi bi-people-fill"></i><span>Alunos cadastrados</span><strong>{{ $totalStudents }}</strong><small>Dados atuais da academia</small></div>
        <div class="gym-metric gym-metric-success"><i class="bi bi-person-workspace"></i><span>Instrutores presentes</span><strong>{{ $totalTeachers }}</strong><small>Presença em desenvolvimento</small></div>
        <div class="gym-metric gym-metric-warning"><i class="bi bi-person-plus-fill"></i><span>Alunos cadastrados hoje</span><strong>{{ $todayStudentsCount }}</strong><small>Novos cadastros do dia</small></div>
        <div class="gym-metric gym-metric-purple"><i class="bi bi-journal-text"></i><span>Matrículas ativas</span><strong class="gym-metric-development">Em desenvolvimento</strong><small>Informação indisponível</small></div>
        <div class="gym-metric gym-metric-orange"><i class="bi bi-cash-stack"></i><span>Receita do mês</span><strong class="gym-metric-development">Em desenvolvimento</strong><small>Informação indisponível</small></div>
    </div>

    <div class="row gym-dashboard-overview">
        <div class="col-12 col-xl-6">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header">
                    <span class="gym-panel-icon text-primary"><i class="bi bi-bar-chart-fill"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Evolução de alunos</h3><small>Novos alunos cadastrados no período selecionado</small></div>
                    <form method="GET" action="{{ route('dashboard') }}" class="gym-period-filter">
                        <label for="period" class="visually-hidden">Período</label>
                        <select id="period" name="period" onchange="this.form.submit()" aria-label="Período do gráfico">
                            <option value="week" @selected($selectedPeriod === 'week')>Última semana</option>
                            <option value="month" @selected($selectedPeriod === 'month')>Último mês</option>
                            <option value="3m" @selected($selectedPeriod === '3m')>Últimos 3 meses</option>
                            <option value="6m" @selected($selectedPeriod === '6m')>Últimos 6 meses</option>
                            <option value="year" @selected($selectedPeriod === 'year')>Último ano</option>
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
                    @php($unitTotal = max(1, $studentsByUnit->sum('students_count')))
                    @php($firstUnitPercentage = $studentsByUnit->count() > 0 ? ($studentsByUnit->first()->students_count / $unitTotal) * 100 : 0)
                    <div class="gym-unit-chart" style="--gym-unit-first: {{ $firstUnitPercentage }}%" aria-label="Distribuição de alunos por unidade">
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
                    <span class="gym-panel-icon text-primary"><i class="bi bi-calendar-event"></i></span>
                    <div class="gym-panel-heading"><h3 class="card-title">Próximos vencimentos</h3><small>Mensalidades a acompanhar</small></div>
                </div>
                <div class="card-body gym-due-list">
                    <div><span>Hoje</span><strong>Em desenvolvimento</strong></div>
                    <div><span>Amanhã</span><strong>Em desenvolvimento</strong></div>
                    <div><span>Esta semana</span><strong>Em desenvolvimento</strong></div>
                    <div><span>Este mês</span><strong>Em desenvolvimento</strong></div>
                </div>
                    <div class="card-footer text-end"><small class="text-muted">Módulo em desenvolvimento</small></div>
            </div>
        </div>
    </div>

    <div class="row gym-dashboard-lists">
        <div class="col-12 col-xl-4">
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
                                <tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($todayStudents as $student)
                                    <tr>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->user->email }}</td>
                                        <td>{{ $student->created_at?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Nenhum aluno cadastrado hoje.</td></tr>
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
        <div class="col-12 col-xl-4">
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
        <div class="col-12 col-xl-4">
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
                <div class="gym-action gym-action-purple"><i class="bi bi-journal-text"></i><span><strong>Nova matrícula</strong><small>Em desenvolvimento</small></span><i class="bi bi-chevron-right"></i></div>
                <div class="gym-action gym-action-orange"><i class="bi bi-wallet2"></i><span><strong>Registrar pagamento</strong><small>Em desenvolvimento</small></span><i class="bi bi-chevron-right"></i></div>
                <div class="gym-action gym-action-slate"><i class="bi bi-file-earmark-text"></i><span><strong>Ver relatórios</strong><small>Em desenvolvimento</small></span><i class="bi bi-chevron-right"></i></div>
            </div>
        </div>
    </div>
@stop
