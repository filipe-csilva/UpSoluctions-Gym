@extends('adminlte::page')

@section('title', 'Comunicados')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Comunicados</h1>
        @can('manage-announcements')
            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo comunicado
            </a>
        @endcan
    </div>
@stop

@section('content')
    <x-alerts />

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('announcements.index') }}" class="row g-2">
                <div class="col-md-7">
                    <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar comunicado">
                </div>
                @can('manage-announcements')
                    <div class="col-md-3">
                        <select name="active" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" @selected(request('active') === '1')>Ativos</option>
                            <option value="0" @selected(request('active') === '0')>Inativos</option>
                        </select>
                    </div>
                @endcan
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Unidade</th>
                        <th>Destinatários</th>
                        <th>Status</th>
                        <th>Publicação</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->title }}</td>
                            <td>{{ $announcement->unit?->name ?? 'Todas' }}</td>
                            <td>{{ ucfirst($announcement->target_role ?? 'all') }}</td>
                            <td>
                                <span class="status-pill status-pill-{{ $announcement->active ? 'success' : 'neutral' }}">
                                    {{ $announcement->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>{{ $announcement->start_at?->format('d/m/Y H:i') ?? 'Imediata' }}</td>
                            <td class="text-end">
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Visualizar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum comunicado disponível.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">{{ $announcements->links() }}</div>
    </div>
@stop
