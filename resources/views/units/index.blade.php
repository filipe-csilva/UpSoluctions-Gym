@extends('adminlte::page')

@section('title', 'Unidades')

@push('css')
    <style>
        @media (max-width: 767.98px) {
            .units-header {
                align-items: stretch !important;
                flex-direction: column;
                gap: .75rem;
            }

            .units-header .btn {
                width: 100%;
            }

            .units-table thead {
                display: none;
            }

            .units-table tbody tr {
                display: block;
                padding: .75rem;
                border-bottom: 1px solid var(--bs-border-color);
            }

            .units-table tbody td {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: .4rem 0;
                border: 0;
                text-align: right;
            }

            .units-table tbody td::before {
                content: attr(data-label);
                color: var(--bs-secondary-color);
                font-weight: 600;
                text-align: left;
            }

            .units-table tbody td:last-child {
                justify-content: flex-end;
                padding-top: .75rem;
            }

            .units-table tbody td:last-child::before {
                display: none;
            }

            .units-table tbody td .btn {
                width: 100%;
            }

            .units-pagination {
                overflow-x: auto;
                padding-bottom: .25rem;
            }
        }
    </style>
@endpush

@section('content_header')
    <div class="units-header d-flex justify-content-between align-items-center">
        <h1>Unidades</h1>
        <a href="{{ route('units.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova unidade</a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="units-table table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Telefone</th>
                        <th>Cidade/UF</th>
                        <th>Status</th>
                        <th>Usuários</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr>
                            <td data-label="Nome">{{ $unit->name }}</td>
                            <td data-label="Código">{{ $unit->code }}</td>
                            <td data-label="Telefone">{{ $unit->phone ?: '-' }}</td>
                            <td data-label="Cidade/UF">{{ $unit->city ?: '-' }}/{{ $unit->state ?: '-' }}</td>
                            <td data-label="Status">
                                <span class="badge text-bg-{{ $unit->active ? 'success' : 'secondary' }}">
                                    {{ $unit->active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td data-label="Usuários">{{ $unit->users_count }}</td>
                            <td data-label="Ações" class="text-end">
                                <a href="{{ route('units.show', $unit) }}" class="btn btn-sm btn-outline-primary">Visualizar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Nenhuma unidade cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="units-pagination card-footer d-flex justify-content-end">
            {{ $units->links() }}
        </div>
    </div>
@stop
