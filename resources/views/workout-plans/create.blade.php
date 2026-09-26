@extends('adminlte::page')

@section('title', 'Nova ficha de treino')

@section('content_header')
    <h1>Nova ficha de treino</h1>
@stop

@section('content')
    <x-alerts />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('workout-plans.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="student_id">Aluno *</label>
                        <select id="student_id" name="student_id" class="form-select" required>
                            <option value="">Selecione o aluno</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="teacher_id">Instrutor *</label>
                        @if (auth()->user()->role?->value === \App\Enums\UserRole::TEACHER->value)
                            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                            <input id="teacher_id" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        @else
                            <select id="teacher_id" name="teacher_id" class="form-select" required>
                                <option value="">Selecione o instrutor</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nome *</label>
                        <input id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="start_date">Início *</label>
                        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', today()->format('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="active" @selected(old('status', 'active') === 'active')>Ativa</option>
                            <option value="completed" @selected(old('status') === 'completed')>Concluída</option>
                            <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Descrição</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button class="btn btn-success mt-3" type="submit">Salvar</button>
            </form>
        </div>
    </div>
@stop
