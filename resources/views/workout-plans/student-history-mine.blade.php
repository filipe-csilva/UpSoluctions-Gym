@extends('adminlte::page')

@section('title', 'Meu treino')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Meu treino</h1><a href="{{ route('students.me') }}" class="btn btn-secondary">Meu perfil</a></div>
@stop

@section('content')
    <div class="card"><div class="card-header"><h3 class="card-title">Histórico de fichas</h3></div><div class="card-body table-responsive">
        <table class="table table-hover"><thead><tr><th>Ficha</th><th>Instrutor</th><th>Início</th><th>Fim</th><th>Status</th></tr></thead><tbody>
            @forelse($plans as $plan)
                <tr><td>{{ $plan->name }}</td><td>{{ $plan->teacher?->name ?? '-' }}</td><td>{{ $plan->start_date?->format('d/m/Y') ?? '-' }}</td><td>{{ $plan->end_date?->format('d/m/Y') ?? '-' }}</td><td>{{ ucfirst($plan->status) }}</td></tr>
            @empty
                <tr><td colspan="5" class="text-center">Nenhuma ficha encontrada.</td></tr>
            @endforelse
        </tbody></table>
    </div></div>
@stop
