@extends('adminlte::page')

@section('title', 'Avaliações físicas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Avaliações físicas</h1><a href="{{ route('assessments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova avaliação</a></div>
@stop

@section('content')
    <x-alerts />
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Aluno</th><th>Data</th><th>Altura</th><th>Peso</th><th>IMC</th><th>Instrutor</th><th class="text-end">Ações</th></tr></thead><tbody>
        @forelse($assessments as $assessment)
            <tr><td>{{ $assessment->student->user->name }}</td><td>{{ $assessment->assessment_date->format('d/m/Y') }}</td><td>{{ $assessment->height }} m</td><td>{{ $assessment->weight }} kg</td><td>{{ $assessment->bmi ?: '-' }}</td><td>{{ $assessment->teacher->name }}</td><td class="text-end"><a href="{{ route('assessments.history', $assessment->student) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-clock-history"></i> Histórico</a></td></tr>
        @empty
            <tr><td colspan="7" class="text-center">Nenhuma avaliação cadastrada.</td></tr>
        @endforelse
    </tbody></table></div><div class="card-footer d-flex justify-content-end">{{ $assessments->links() }}</div></div>
@stop
