@extends('adminlte::page')

@section('title', 'Dashboard')

@push('js')
    <script>
        window.history.replaceState({}, document.title, '/');
    </script>
@endpush

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Dashboard</h3>
        </div>
        {{-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div> --}}
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm"><i class="bi bi-person-workspace"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Instrutores presentes</span>
                    <span class="info-box-number">{{ $totalTeachers }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm"><i class="bi bi-people-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Alunos cadastrados</span>
                    <span class="info-box-number">{{ $totalStudents }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm"><i class="bi bi-person-plus-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Alunos cadastrados hoje</span>
                    <span class="info-box-number">{{ $todayStudentsCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card h-100 mb-4">
                <div class="card-header">
                    <h3 class="card-title">Alunos cadastrados hoje</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.index') }}" class="btn btn-tool" title="Ver todos os alunos">
                            <i class="bi bi-arrow-up-right-square"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm m-0 small">
                            <thead>
                                <tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($todayStudents as $student)
                                    <tr>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->user->email }}</td>
                                        <td>{{ $student->created_at?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Nenhum aluno cadastrado hoje.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary float-end">Ver todos os alunos</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 mb-4">
                <div class="card-header">
                    <h3 class="card-title">Instrutores presentes</h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.index') }}" class="btn btn-tool" title="Ver todos os instrutores">
                            <i class="bi bi-arrow-up-right-square"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm m-0 small">
                            <thead>
                                <tr><th>Nome</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($latestTeachers as $teacher)
                                    <tr><td>{{ $teacher->user?->name ?? '-' }}</td></tr>
                                @empty
                                    <tr><td class="text-center text-muted">Nenhum instrutor presente.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-primary float-end">Ver todos os instrutores</a>
                </div>
            </div>
        </div>
    </div>
@stop
