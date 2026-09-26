@extends('adminlte::page')

@section('title', 'Minha matrícula')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Minha matrícula</h1><a href="{{ route('students.me') }}" class="btn btn-secondary">Meu perfil</a></div>
@stop

@section('content')
    <div class="card"><div class="card-header"><h3 class="card-title">Histórico de planos</h3></div><div class="card-body table-responsive">
        <table class="table table-hover"><thead><tr><th>Plano</th><th>Unidade</th><th>Período</th><th>Valor</th><th>Status</th></tr></thead><tbody>
            @forelse($enrollments as $enrollment)
                <tr><td>{{ $enrollment->plan->name }}</td><td>{{ $enrollment->unit->name }}</td><td>{{ $enrollment->start_date->format('d/m/Y') }} a {{ $enrollment->end_date->format('d/m/Y') }}</td><td>R$ {{ number_format((float) $enrollment->price, 2, ',', '.') }}</td><td>{{ ucfirst($enrollment->status) }}</td></tr>
            @empty
                <tr><td colspan="5" class="text-center">Nenhuma matrícula encontrada.</td></tr>
            @endforelse
        </tbody></table>
    </div></div>
@stop
