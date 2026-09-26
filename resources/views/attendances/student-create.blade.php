@extends('adminlte::page')

@section('title', 'Registrar treino')

@section('content_header')
    <h1>Registrar treino</h1>
@stop

@section('content')
    <x-alerts />

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Escolha a unidade onde você realizará o treino de hoje.</p>
            <form method="POST" action="{{ route('student-attendance.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="unit_id">Unidade *</label>
                    <select id="unit_id" name="unit_id" class="form-select" required>
                        <option value="">Selecione a unidade</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Registrar entrada</button>
            </form>
        </div>
    </div>
@stop
