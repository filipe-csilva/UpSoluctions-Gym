@extends('adminlte::page')

@section('title', 'Alunos')
@push('js')
    <script>
        window.history.replaceState({}, document.title, '/');
    </script>
@endpush

@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

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

        <div class="card-body table-responsive p-0">

            <table class="table table-hover">

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

                                <td>
                                    {{ $student->user->name }}
                                </td>

                                <td>
                                    {{ $student->cpf }}
                                </td>

                                <td>
                                    {{ $student->user->email }}
                                </td>

                                <td>
                                    {{ $student->phone }}
                                </td>

                                <td>
                                    {{ $student->user->unit?->name ?? '-' }}
                                </td>

                                <td class="text-end">
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
            {{ $students->links() }}
        </div>

    </div>
@stop
