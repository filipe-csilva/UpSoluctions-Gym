@extends('adminlte::page')

@section('title', 'Editar aluno')

@section('content_header')<h1>Editar aluno</h1>@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf @method('PUT')
        <div class="card"><div class="card-body"><div class="row">
            <div class="form-group col-md-6"><label for="name">Nome</label><input id="name" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required></div>
            <div class="form-group col-md-6"><label for="email">E-mail</label><input id="email" name="email" type="email" class="form-control" value="{{ old('email', $student->user->email) }}" required></div>
            <div class="form-group col-md-6"><label for="unit_id">Unidade</label><select id="unit_id" name="unit_id" class="form-control" required>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $student->user->unit_id) == $unit->id)>{{ $unit->name }}</option>@endforeach</select></div>
            <div class="form-group col-md-6"><label for="cpf">CPF</label><input id="cpf" name="cpf" class="form-control" value="{{ old('cpf', $student->cpf) }}" required></div>
            <div class="form-group col-md-4"><label for="birth_date">Nascimento</label><input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}" required></div>
            <div class="form-group col-md-4"><label for="phone">Telefone</label><input id="phone" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}" required></div>
            <div class="form-group col-md-4"><label for="gender">Gênero</label><input id="gender" name="gender" class="form-control" value="{{ old('gender', $student->gender) }}"></div>
            <div class="form-group col-md-8"><label for="andress">Endereço</label><input id="andress" name="andress" class="form-control" value="{{ old('andress', $student->andress) }}"></div>
            <div class="form-group col-md-4"><label for="number">Número</label><input id="number" name="number" class="form-control" value="{{ old('number', $student->number) }}"></div>
            <div class="form-group col-md-4"><label for="neighborhood">Bairro</label><input id="neighborhood" name="neighborhood" class="form-control" value="{{ old('neighborhood', $student->neighborhood) }}"></div>
            <div class="form-group col-md-4"><label for="city">Cidade</label><input id="city" name="city" class="form-control" value="{{ old('city', $student->city) }}"></div>
            <div class="form-group col-md-2"><label for="state">UF</label><input id="state" name="state" maxlength="2" class="form-control" value="{{ old('state', $student->state) }}"></div>
            <div class="form-group col-md-2"><label for="zip_code">CEP</label><input id="zip_code" name="zip_code" class="form-control" value="{{ old('zip_code', $student->zip_code) }}"></div>
            <div class="form-group col-md-6"><label for="emergency_contact">Contato de emergência</label><input id="emergency_contact" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $student->emergency_contact) }}"></div>
            <div class="form-group col-md-6"><label for="emergency_phone">Telefone de emergência</label><input id="emergency_phone" name="emergency_phone" class="form-control" value="{{ old('emergency_phone', $student->emergency_phone) }}"></div>
            <div class="form-group col-12"><label for="notes">Observações</label><textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $student->notes) }}</textarea></div>
        </div></div><div class="card-footer"><button class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar alterações</button> <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">Cancelar</a></div></div>
    </form>
@stop
