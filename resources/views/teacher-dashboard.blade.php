@extends('adminlte::page')

@section('title', 'Dashboard do instrutor')

@section('content_header')
    <div><h1>Dashboard do instrutor</h1><p class="text-muted mb-0">Acompanhe seus alunos, treinos e atividades da unidade.</p></div>
@stop

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="small-box bg-primary"><div class="inner"><h3>{{ $teacherStudentCount }}</h3><p>Meus alunos</p></div><div class="icon"><i class="bi bi-people-fill"></i></div><a href="{{ route('teachers.my-students') }}" class="small-box-footer">Acessar <i class="bi bi-arrow-right"></i></a></div></div>
        <div class="col-md-4"><div class="small-box bg-success"><div class="inner"><h3>{{ $teacherWorkoutCount }}</h3><p>Treinos ativos</p></div><div class="icon"><i class="bi bi-clipboard2-pulse"></i></div><a href="{{ route('workout-plans.index') }}" class="small-box-footer">Gerenciar <i class="bi bi-arrow-right"></i></a></div></div>
        <div class="col-md-4"><div class="small-box bg-info"><div class="inner"><h3>{{ $teacherAssessmentCount }}</h3><p>Avaliações registradas</p></div><div class="icon"><i class="bi bi-clipboard2-data"></i></div><a href="{{ route('assessments.index') }}" class="small-box-footer">Consultar <i class="bi bi-arrow-right"></i></a></div></div>
    </div>

    <div class="card mb-4 gym-dashboard-panel"><div class="card-header"><span class="gym-panel-icon text-success"><i class="bi bi-people-fill"></i></span><div class="gym-panel-heading"><h2 class="card-title">Alunos presentes hoje</h2><small>Alunos da sua unidade com presença registrada</small></div></div><div class="card-body">
        @forelse ($presentStudents as $attendance)
            <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $attendance->student?->user?->name ?? 'Aluno' }}</span><small class="text-muted">Entrada {{ $attendance->entry_time ?? '-' }}</small></div>
        @empty
            <p class="text-muted mb-0">Nenhum aluno presente registrado hoje.</p>
        @endforelse
    </div></div>

    <div class="row g-4">
        @forelse ($announcements as $announcement)
            <div class="col-12 col-md-6"><article class="card h-100 gym-dashboard-panel"><div class="card-header"><span class="gym-panel-icon text-warning"><i class="bi bi-megaphone-fill"></i></span><div class="gym-panel-heading"><h2 class="card-title">{{ $announcement->title }}</h2><small>{{ $announcement->unit?->name ?? 'Todas as unidades' }}</small></div></div><div class="card-body"><p class="mb-3">{!! nl2br(e($announcement->message)) !!}</p><small class="text-muted">Publicado em {{ $announcement->start_at?->format('d/m/Y H:i') ?? 'Imediatamente' }}</small></div></article></div>
        @empty
            <div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5">Nenhum comunicado disponível.</div></div></div>
        @endforelse
    </div>
@stop
