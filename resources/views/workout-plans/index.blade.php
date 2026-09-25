@extends('adminlte::page')
@section('title', 'Fichas de treino')
@section('content_header')<div class="d-flex justify-content-between align-items-center"><h1>Fichas de treino</h1><a href="{{ route('workout-plans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova ficha</a></div>@stop
@section('content')
    <x-alerts />
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Aluno</th><th>Ficha</th><th>Instrutor</th><th>Status</th><th></th></tr></thead><tbody>@forelse($plans as $plan)<tr><td>{{ $plan->student->user->name }}</td><td>{{ $plan->name }}</td><td>{{ $plan->teacher->name }}</td><td><span class="status-pill status-pill-{{ $plan->status === 'active' ? 'success' : ($plan->status === 'cancelled' ? 'danger' : 'neutral') }}">{{ ['active' => 'Ativa', 'completed' => 'Concluída', 'cancelled' => 'Cancelada'][$plan->status] ?? ucfirst($plan->status) }}</span></td><td class="text-end"><a href="{{ route('workout-plans.show', $plan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Visualizar</a></td></tr>@empty<tr><td colspan="5" class="text-center">Nenhuma ficha cadastrada.</td></tr>@endforelse</tbody></table></div><div class="card-footer d-flex justify-content-end">{{ $plans->links() }}</div></div>
@stop
