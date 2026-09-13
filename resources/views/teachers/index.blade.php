@extends('adminlte::page')

@section('title', 'Instrutores')
@push('css')
    <style>
        @media (max-width: 767.98px) {
            .students-header {
                align-items: stretch !important;
                flex-direction: column;
                gap: .75rem;
            }

            .students-header .btn {
                width: 100%;
            }

            .students-table thead {
                display: none;
            }

            .students-table tbody tr {
                display: block;
                border-bottom: 1px solid var(--bs-border-color);
                padding: .75rem;
            }

            .students-table tbody td {
                align-items: center;
                border: 0;
                display: flex;
                justify-content: space-between;
                gap: 1rem;
                padding: .4rem 0;
                text-align: right;
            }

            .students-table tbody td::before {
                color: var(--bs-secondary-color);
                content: attr(data-label);
                font-weight: 600;
                text-align: left;
            }

            .students-table tbody td:first-child {
                font-size: 1rem;
                font-weight: 600;
            }

            .students-table tbody td:last-child {
                justify-content: flex-end;
                padding-top: .75rem;
            }

            .students-table tbody td:last-child::before {
                display: none;
            }

            .students-table tbody td .btn {
                width: 100%;
            }

            .students-pagination {
                overflow-x: auto;
                padding-bottom: .25rem;
            }
        }
    </style>
@endpush
@push('js')
    <script>
        window.history.replaceState({}, document.title, '/');
    </script>
@endpush

@section('content_header')

    <div class="teachers-header d-flex justify-content-between align-items-center">

        <h1>Instrutores</h1>

        <a
            href="{{ route('teachers.create') }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Novo Instrutor
        </a>

    </div>

@stop

@section('content')
    <x-alerts />

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-5"><label for="search" class="form-label">Buscar</label><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nome, e-mail ou CPF"></div>
                <div class="col-12 col-md-3"><label for="unit_id" class="form-label">Unidade</label><select id="unit_id" name="unit_id" class="form-select"><option value="">Todas</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(request('unit_id') == $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
                <div class="col-12 col-md-2"><label for="active" class="form-label">Status</label><select id="active" name="active" class="form-select"><option value="">Todos</option><option value="1" @selected(request('active') === '1')>Ativos</option><option value="0" @selected(request('active') === '0')>Inativos</option></select></div>
                <div class="col-12 col-md-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Filtrar</button><a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Limpar</a></div>
            </form>
        </div>
    </div>

    <div class="card">

        <div class="card-body table-responsive">

            <table class="students-table table table-hover mb-0">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Unidade</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($teachers as $teacher)
                            <tr>

                                <td data-label="Nome">
                                    {{ $teacher->user->name }}
                                </td>

                                <td data-label="CPF">
                                    {{ $teacher->cpf }}
                                </td>

                                <td data-label="E-mail">
                                    {{ $teacher->user->email }}
                                </td>

                                <td data-label="Telefone">
                                    {{ $teacher->phone }}
                                </td>

                                <td data-label="Unidade">
                                    {{ $teacher->user->unit?->name ?? '-' }}
                                </td>

                                <td data-label="Status">
                                    <span class="badge text-bg-{{ $teacher->user->active ? 'success' : 'secondary' }}">
                                        {{ $teacher->user->active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>

                                <td data-label="Ações" class="text-end">
                                    <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-outline-primary" title="Visualizar instrutor">
                                        <i class="bi bi-eye"></i>
                                        Visualizar
                                    </a>
                                    <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" class="d-inline" onsubmit="return confirm('Deseja excluir este instrutor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir Instrutor"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>

                            </tr>

                    @empty

                        <tr>
                            <td
                                colspan="7"
                                class="text-center"
                            >
                                Nenhum instrutor cadastrado.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer">
            <div class="teachers-pagination d-flex justify-content-end">
                {{ $teachers->links() }}
            </div>
        </div>

    </div>
@stop
