@extends('adminlte::page')

@section('title', 'Cadastrar Aluno')

@push('js')
    <script>
        window.history.replaceState({}, document.title, '/');
    </script>
@endpush

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Cadastrar Aluno</h1>

        <a href="{{ route('students.index') }}"
           class="btn btn-secondary">
            Voltar
        </a>
    </div>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Verifique os campos abaixo:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('students.store') }}">

        @csrf

        {{-- Dados de acesso --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Dados de acesso
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome *</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unidade *</label>

                            <select
                                name="unit_id"
                                class="form-control"
                                required
                            >
                                <option value="">
                                    Selecione
                                </option>

                                @foreach ($units as $unit)
                                    <option
                                        value="{{ $unit->id }}"
                                        @selected(old('unit_id') == $unit->id)
                                    >
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>E-mail *</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Senha *</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Confirmar senha *</label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <br>
        {{-- Dados pessoais --}}
        <div class="card card-info">

            <div class="card-header">
                <h3 class="card-title">
                    Dados pessoais
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>CPF *</label>

                            <input
                                type="text"
                                name="cpf"
                                class="form-control"
                                value="{{ old('cpf') }}"
                                maxlength="14"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de nascimento *</label>

                            <input
                                type="date"
                                name="birth_date"
                                class="form-control"
                                value="{{ old('birth_date') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Telefone *</label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone') }}"
                                required
                            >
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gênero</label>

                            <select
                                name="gender"
                                class="form-control"
                            >
                                <option value="">
                                    Não informado
                                </option>

                                <option
                                    value="Masculino"
                                    @selected(old('gender') === 'Masculino')
                                >
                                    Masculino
                                </option>

                                <option
                                    value="Feminino"
                                    @selected(old('gender') === 'Feminino')
                                >
                                    Feminino
                                </option>

                                <option
                                    value="Outro"
                                    @selected(old('gender') === 'Outro')
                                >
                                    Outro
                                </option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <br>
        {{-- Endereço --}}
        <div class="card card-secondary">

            <div class="card-header">
                <h3 class="card-title">
                    Endereço
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>CEP</label>

                            <input
                                type="text"
                                name="zip_code"
                                class="form-control"
                                value="{{ old('zip_code') }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Endereço</label>

                            <input
                                type="text"
                                name="address"
                                class="form-control"
                                value="{{ old('address') }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Número</label>

                            <input
                                type="text"
                                name="number"
                                class="form-control"
                                value="{{ old('number') }}"
                            >
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Bairro</label>

                            <input
                                type="text"
                                name="neighborhood"
                                class="form-control"
                                value="{{ old('neighborhood') }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cidade</label>

                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="{{ old('city') }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>UF</label>

                            <input
                                type="text"
                                name="state"
                                class="form-control"
                                maxlength="2"
                                value="{{ old('state') }}"
                            >
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <br>
        {{-- Emergência --}}
        <div class="card card-warning">

            <div class="card-header">
                <h3 class="card-title">
                    Contato de emergência
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contato</label>

                            <input
                                type="text"
                                name="emergency_contact"
                                class="form-control"
                                value="{{ old('emergency_contact') }}"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telefone</label>

                            <input
                                type="text"
                                name="emergency_phone"
                                class="form-control"
                                value="{{ old('emergency_phone') }}"
                            >
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label>Observações</label>

                    <textarea
                        name="notes"
                        class="form-control"
                        rows="3"
                    >{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>
        <br>
        <div class="text-right pb-4">
            <button
                type="submit"
                class="btn btn-success"
            >
                <i class="fas fa-save"></i>
                Cadastrar aluno
            </button>
        </div>

    </form>

@stop