@extends('adminlte::page')

@section('title', 'Notícias')

@section('content_header')
    <div>
        <h1>Notícias e comunicados</h1>
        <p class="text-muted mb-0">Informações importantes da academia.</p>
    </div>
@stop

@section('content')
    @if (auth()->user()->role?->value === 'teacher')
        <div class="card mb-4 gym-dashboard-panel">
            <div class="card-header">
                <span class="gym-panel-icon text-success"><i class="bi bi-people-fill"></i></span>
                <div class="gym-panel-heading">
                    <h2 class="card-title">Alunos presentes hoje</h2>
                    <small>Alunos da sua unidade com presença registrada</small>
                </div>
            </div>
            <div class="card-body">
                @forelse ($presentStudents as $attendance)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $attendance->student?->user?->name ?? 'Aluno' }}</span>
                        <small class="text-muted">Entrada {{ $attendance->entry_time ?? '-' }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nenhum aluno presente registrado hoje.</p>
                @endforelse
            </div>
        </div>
    @elseif (auth()->user()->role?->value === 'student')
        <div class="card mb-4 gym-dashboard-panel">
            <div class="card-header">
                <span class="gym-panel-icon text-primary"><i class="bi bi-clipboard2-pulse"></i></span>
                <div class="gym-panel-heading">
                    <h2 class="card-title">Meu treino</h2>
                    <small>Ficha de treino destinada a você</small>
                </div>
            </div>
            <div class="card-body">
                @if ($workoutPlan)
                    <h3 class="h5 mb-1">{{ $workoutPlan->name }}</h3>
                    <p class="text-muted mb-3">Instrutor: {{ $workoutPlan->teacher?->name ?? '-' }}</p>
                    @forelse ($workoutPlan->exercises as $workoutExercise)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $workoutExercise->exercise?->name ?? 'Exercício' }}</span>
                            <small class="text-muted">{{ $workoutExercise->sets }} séries · {{ $workoutExercise->repetitions }} repetições</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Sua ficha ainda não possui exercícios.</p>
                    @endforelse
                @else
                    <p class="text-muted mb-0">Nenhuma ficha de treino ativa disponível.</p>
                @endif
                <a href="{{ route('student-attendance.create') }}" class="btn btn-primary mt-3">Registrar treino em uma unidade</a>
                <a href="{{ route('assessments.mine') }}" class="btn btn-outline-primary mt-3 ms-2">Histórico de avaliação</a>
                <a href="{{ route('student-financial.index') }}" class="btn btn-outline-success mt-3 ms-2">Meu financeiro</a>
            </div>
        </div>
    @endif

    <div class="row g-4">
        @forelse ($announcements as $announcement)
            <div class="col-12 col-md-6 {{ $announcements->count() === 1 ? 'col-xl-12' : ($announcements->count() === 2 ? 'col-xl-6' : 'col-xl-4') }}">
                <article class="card h-100 gym-dashboard-panel">
                    <div class="card-header">
                        <span class="gym-panel-icon text-warning"><i class="bi bi-megaphone-fill"></i></span>
                        <div class="gym-panel-heading">
                            <h2 class="card-title">{{ $announcement->title }}</h2>
                            <small>{{ $announcement->unit?->name ?? 'Todas as unidades' }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">{!! nl2br(e($announcement->message)) !!}</p>
                        <small class="text-muted">
                            Publicado em {{ $announcement->start_at?->format('d/m/Y H:i') ?? 'Imediatamente' }}
                        </small>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-megaphone fs-1 d-block mb-3"></i>
                        Nenhuma notícia ou comunicado disponível no momento.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($announcements->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $announcements->links() }}
        </div>
    @endif
@stop
