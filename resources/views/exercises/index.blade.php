@extends('adminlte::page')

@section('title', 'Exercícios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Exercícios</h1>
        <a href="{{ route('exercises.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo exercício</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card mb-3"><div class="card-body"><form class="row g-2"><div class="col-md-8"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Nome ou grupo muscular"></div><div class="col-md-2"><select name="active" class="form-select"><option value="">Todos</option><option value="1" @selected(request('active') === '1')>Ativos</option><option value="0" @selected(request('active') === '0')>Inativos</option></select></div><div class="col-md-2"><button class="btn btn-primary w-100">Filtrar</button></div></form></div></div>
    <div class="card"><div class="card-body table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Nome</th><th>Grupo muscular</th><th>Equipamento</th><th>Status</th><th></th></tr></thead><tbody>@forelse($exercises as $exercise)<tr><td>{{ $exercise->name }}</td><td>{{ $exercise->muscle_group }}</td><td>{{ $exercise->equipment ?: '-' }}</td><td><span class="status-pill status-pill-{{ $exercise->active ? 'success' : 'neutral' }}">{{ $exercise->active ? 'Ativo' : 'Inativo' }}</span></td><td class="text-end"><a href="{{ route('exercises.show', $exercise) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Visualizar</a></td></tr>@empty<tr><td colspan="5" class="text-center">Nenhum exercício cadastrado.</td></tr>@endforelse</tbody></table></div><div class="card-footer d-flex justify-content-end">{{ $exercises->links() }}</div></div>
@stop
