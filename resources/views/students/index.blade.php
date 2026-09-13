@extends('adminlte::page')

@section('title', 'Alunos')
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

    <div class="students-header d-flex justify-content-between align-items-center">

        <h1>Alunos</h1>

        <a
            href="{{ route('students.create') }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Novo aluno
        </a>

    </div>

@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($students as $student)
                            <tr>

                                <td data-label="Nome">
                                    {{ $student->user->name }}
                                </td>

                                <td data-label="CPF">
                                    {{ $student->cpf }}
                                </td>

                                <td data-label="E-mail">
                                    {{ $student->user->email }}
                                </td>

                                <td data-label="Telefone">
                                    {{ $student->phone }}
                                </td>

                                <td data-label="Unidade">
                                    {{ $student->user->unit?->name ?? '-' }}
                                </td>

                                <td data-label="Ações" class="text-end">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" title="Visualizar aluno">
                                        <i class="bi bi-eye"></i>
                                        Visualizar
                                    </a>
                                </td>

                            </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="text-center"
                            >
                                Nenhum aluno cadastrado.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer">
            <div class="students-pagination d-flex justify-content-end">
                {{ $students->links() }}
            </div>
        </div>

    </div>
@stop
