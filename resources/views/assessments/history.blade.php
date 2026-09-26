@extends('adminlte::page')

@section('title', 'Histórico de avaliação física')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><div><h1>Histórico de avaliação física</h1><p class="text-muted mb-0">{{ $student->user->name }}</p></div><div class="d-flex gap-2"><a href="{{ route('assessments.comparison', $student) }}" class="btn btn-info">Comparar evolução</a><a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a></div></div>
@stop

@section('content')
    <x-alerts />
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Data</th><th>Altura</th><th>Peso</th><th>IMC</th><th>Gordura</th><th>Massa muscular</th><th>Instrutor</th><th>Observações</th></tr></thead><tbody>
        @forelse($assessments as $assessment)
            <tr><td>{{ $assessment->assessment_date->format('d/m/Y') }}</td><td>{{ $assessment->height }} m</td><td>{{ $assessment->weight }} kg</td><td>{{ $assessment->bmi ?: '-' }}</td><td>{{ $assessment->body_fat !== null ? $assessment->body_fat.'%' : '-' }}</td><td>{{ $assessment->muscle_mass !== null ? $assessment->muscle_mass.' kg' : '-' }}</td><td>{{ $assessment->teacher?->name ?? '-' }}</td><td>{{ $assessment->notes ?: '-' }}</td></tr>
        @empty
            <tr><td colspan="8" class="text-center">Nenhuma avaliação encontrada.</td></tr>
        @endforelse
    </tbody></table></div></div>
@stop
