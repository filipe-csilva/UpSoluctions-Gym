@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="gym-dashboard-heading">
        <div><h1>Bem-vindo de volta, {{ auth()->user()->name }}!</h1><p>Este é o resumo dos seus alunos e atividades.</p></div>
        <div class="gym-dashboard-meta"><span class="gym-dashboard-eyebrow">{{ now()->locale('pt_BR')->translatedFormat('l, d \d\e F \d\e Y') }}</span><span class="gym-dashboard-motto">"Disciplina hoje, resultados sempre!" <i class="bi bi-universal-access"></i></span></div>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="gym-dashboard-metrics">
        <div class="gym-metric gym-metric-primary"><i class="bi bi-people-fill"></i><span>Meus alunos</span><strong>{{ $teacherStudentCount }}</strong><small>Alunos vinculados às suas fichas</small></div>
        <div class="gym-metric gym-metric-success"><i class="bi bi-clipboard2-pulse"></i><span>Treinos ativos</span><strong>{{ $teacherWorkoutCount }}</strong><small>Fichas em acompanhamento</small></div>
        <div class="gym-metric gym-metric-purple"><i class="bi bi-clipboard2-data"></i><span>Avaliações físicas</span><strong>{{ $teacherAssessmentCount }}</strong><small>Registros dos seus alunos</small></div>
        <div class="gym-metric gym-metric-warning"><i class="bi bi-calendar-check"></i><span>Frequências hoje</span><strong>{{ $teacherAttendanceCount }}</strong><small>Registros na sua unidade</small></div>
    </div>

    <div class="row gym-dashboard-overview">
        <div class="col-12 col-xl-6">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header"><span class="gym-panel-icon text-success"><i class="bi bi-people-fill"></i></span><div class="gym-panel-heading"><h3 class="card-title">Alunos presentes hoje</h3><small>Alunos da sua unidade com presença registrada</small></div><a href="{{ route('attendances.index') }}" class="gym-view-all">Ver frequência <i class="bi bi-arrow-right"></i></a></div>
                <div class="card-body">
                    @forelse ($presentStudents as $attendance)
                        <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $attendance->student?->user?->name ?? 'Aluno' }}</span><small class="text-muted">Entrada {{ $attendance->entry_time ?? '-' }}</small></div>
                    @empty
                        <p class="text-muted mb-0">Nenhum aluno presente registrado hoje.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card gym-dashboard-panel h-100">
                <div class="card-header"><span class="gym-panel-icon text-primary"><i class="bi bi-lightning-charge-fill"></i></span><div class="gym-panel-heading"><h3 class="card-title">Ações rápidas</h3><small>Acesse as rotinas mais utilizadas</small></div></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('teachers.my-students') }}" class="gym-action"><i class="bi bi-people-fill text-primary"></i><span><strong>Meus alunos</strong><small>Consultar alunos vinculados</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
                    <a href="{{ route('workout-plans.create') }}" class="gym-action"><i class="bi bi-clipboard2-pulse text-success"></i><span><strong>Adicionar treino</strong><small>Criar ficha para um aluno</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
                    <a href="{{ route('assessments.create') }}" class="gym-action"><i class="bi bi-clipboard2-data text-info"></i><span><strong>Nova avaliação</strong><small>Registrar avaliação física</small></span><i class="bi bi-chevron-right ms-auto"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($announcements as $announcement)
            <div class="col-12 col-md-6"><article class="card h-100 gym-dashboard-panel"><div class="card-header"><span class="gym-panel-icon text-warning"><i class="bi bi-megaphone-fill"></i></span><div class="gym-panel-heading"><h3 class="card-title">{{ $announcement->title }}</h3><small>{{ $announcement->unit?->name ?? 'Todas as unidades' }}</small></div></div><div class="card-body"><p class="mb-3">{!! nl2br(e($announcement->message)) !!}</p><small class="text-muted">Publicado em {{ $announcement->start_at?->format('d/m/Y H:i') ?? 'Imediatamente' }}</small></div></article></div>
        @empty
            <div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5">Nenhum comunicado disponível.</div></div></div>
        @endforelse
    </div>
@stop
