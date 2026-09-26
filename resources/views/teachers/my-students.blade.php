@extends('adminlte::page')

@section('title', 'Meus alunos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div><h1>Meus alunos</h1><p class="text-muted mb-0">Alunos vinculados às suas fichas de treino.</p></div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar ao dashboard</a>
    </div>
@stop

@section('content')
    <div class="card"><div class="card-header"><strong>{{ $teacher->name }}</strong></div><div class="card-body table-responsive">
        <table class="table table-hover"><thead><tr><th>Aluno</th><th>Unidade</th><th>Treino atual</th><th>Período</th><th>Ações</th></tr></thead><tbody>
            @forelse($students as $plans)
                @php($plan = $plans->first())
                <tr><td>{{ $plan->student->user->name }}</td><td>{{ $plan->student->user->unit?->name ?? '-' }}</td><td>{{ $plan->name }}</td><td>{{ $plan->start_date?->format('d/m/Y') ?? '-' }} a {{ $plan->end_date?->format('d/m/Y') ?? '-' }}</td><td><a href="{{ route('workout-plans.student-history', $plan->student) }}" class="btn btn-sm btn-outline-primary">Ver ficha</a></td></tr>
            @empty
                <tr><td colspan="5" class="text-center">Nenhum aluno vinculado às suas fichas.</td></tr>
            @endforelse
        </tbody></table>
    </div></div>
@stop
