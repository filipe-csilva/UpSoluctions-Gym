@csrf
@if ($unit->exists)
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-4">
        <label for="name" class="form-label">Nome</label>
        <input id="name" name="name" value="{{ old('name', $unit->name) }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="code" class="form-label">Código</label>
        <input id="code" name="code" value="{{ old('code', $unit->code) }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="phone" class="form-label">Telefone</label>
        <input id="phone" name="phone" value="{{ old('phone', $unit->phone) }}" class="form-control">
    </div>
    <div class="col-md-8">
        <label for="email" class="form-label">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email', $unit->email) }}" class="form-control">
    </div>
    <div class="col-md-9">
        <label for="address" class="form-label">Endereço</label>
        <input id="address" name="address" value="{{ old('address', $unit->address) }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label for="number" class="form-label">Número</label>
        <input id="number" name="number" value="{{ old('number', $unit->number) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="neighborhood" class="form-label">Bairro</label>
        <input id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $unit->neighborhood) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="city" class="form-label">Cidade</label>
        <input id="city" name="city" value="{{ old('city', $unit->city) }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label for="state" class="form-label">UF</label>
        <input id="state" name="state" value="{{ old('state', $unit->state) }}" maxlength="2" class="form-control">
    </div>
    <div class="col-md-2">
        <label for="zip_code" class="form-label">CEP</label>
        <input id="zip_code" name="zip_code" value="{{ old('zip_code', $unit->zip_code) }}" class="form-control">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input id="active" type="checkbox" name="active" value="1" class="form-check-input" @checked(old('active', $unit->exists ? $unit->active : true))>
            <label for="active" class="form-check-label">Unidade ativa</label>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-actions d-flex flex-wrap gap-2 mt-4">
    <button class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar</button>
    <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
