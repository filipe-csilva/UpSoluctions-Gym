@extends('adminlte::page')
@section('title', 'Configurações gerais')
@section('content_header')<h1>Configurações gerais</h1>@stop
@section('content')
<x-alerts />
<div class="card"><div class="card-body"><form method="POST" action="{{ route('settings.update') }}">@csrf @method('PUT')<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome da empresa *</label><input name="company_name" class="form-control" required value="{{ old('company_name', $settings['company_name'] ?? config('app.name')) }}"></div>
<div class="col-md-6"><label class="form-label">CNPJ</label><input name="company_document" class="form-control" value="{{ old('company_document', $settings['company_document'] ?? '') }}"></div>
<div class="col-md-4"><label class="form-label">Telefone</label><input name="company_phone" class="form-control" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}"></div>
<div class="col-md-4"><label class="form-label">E-mail</label><input type="email" name="company_email" class="form-control" value="{{ old('company_email', $settings['company_email'] ?? '') }}"></div>
<div class="col-md-4"><label class="form-label">Fuso horário *</label><select name="timezone" class="form-select" required><option value="America/Fortaleza" @selected(old('timezone', $settings['timezone'] ?? config('app.timezone')) === 'America/Fortaleza')>Brasília (Fortaleza)</option><option value="America/Sao_Paulo" @selected(old('timezone', $settings['timezone'] ?? '') === 'America/Sao_Paulo')>Brasília (São Paulo)</option></select></div>
<div class="col-12"><label class="form-label">Endereço</label><input name="company_address" class="form-control" value="{{ old('company_address', $settings['company_address'] ?? '') }}"></div>
</div><button class="btn btn-success mt-3"><i class="bi bi-check-lg"></i> Salvar configurações</button></form></div></div>@stop
