@extends('adminlte::page')
@section('title', 'Planos')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>Planos</h1><a href="{{ route('plans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo plano</a></div>
@stop
@section('content')
    <x-alerts />
    <div class="card mb-3"><div class="card-body"><form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-7"><label for="search" class="form-label">Buscar</label><input id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nome do plano"></div>
        <div class="col-12 col-md-3"><label for="active" class="form-label">Status</label><select id="active" name="active" class="form-select"><option value="">Todos</option><option value="1" @selected(request('active') === '1')>Ativos</option><option value="0" @selected(request('active') === '0')>Inativos</option></select></div>
        <div class="col-12 col-md-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Filtrar</button><a href="{{ route('plans.index') }}" class="btn btn-outline-secondary">Limpar</a></div>
    </form></div></div>
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Nome</th><th>Duração</th><th>Valor</th><th>Status</th><th class="text-end">Ações</th></tr></thead><tbody>
        @forelse($plans as $plan)<tr><td>{{ $plan->name }}</td><td>{{ $plan->duration_months }} {{ $plan->duration_months === 1 ? 'mês' : 'meses' }}</td><td>R$ {{ number_format((float) $plan->price, 2, ',', '.') }}</td><td><span class="badge text-bg-{{ $plan->active ? 'success' : 'secondary' }}">{{ $plan->active ? 'Ativo' : 'Inativo' }}</span></td><td class="text-end"><a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Visualizar</a></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">Nenhum plano cadastrado.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer d-flex justify-content-end">{{ $plans->links() }}</div></div>
@stop
