@extends('adminlte::page')

@section('title', 'Configurações gerais')

@section('content_header')
    <h1>Configurações gerais</h1>
@stop

@section('content')
    <x-alerts />
    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-building me-2"></i>Identidade da empresa</strong></div>
            <div class="card-body row g-3">
                <div class="col-md-6"><label class="form-label">Nome da empresa *</label><input name="company_name" class="form-control" required value="{{ old('company_name', $settings['company_name']) }}"></div>
                <div class="col-md-6"><label class="form-label">CNPJ</label><input name="company_document" class="form-control" value="{{ old('company_document', $settings['company_document']) }}"></div>
                <div class="col-md-4"><label class="form-label">Telefone</label><input name="company_phone" class="form-control" value="{{ old('company_phone', $settings['company_phone']) }}"></div>
                <div class="col-md-4"><label class="form-label">E-mail</label><input type="email" name="company_email" class="form-control" value="{{ old('company_email', $settings['company_email']) }}"></div>
                <div class="col-md-4"><label class="form-label">Moeda *</label><select name="currency" class="form-select"><option value="BRL" @selected(old('currency', $settings['currency']) === 'BRL')>Real brasileiro (BRL)</option><option value="USD" @selected(old('currency', $settings['currency']) === 'USD')>Dólar (USD)</option><option value="EUR" @selected(old('currency', $settings['currency']) === 'EUR')>Euro (EUR)</option></select></div>
                <div class="col-md-8"><label class="form-label">Endereço</label><input name="company_address" class="form-control" value="{{ old('company_address', $settings['company_address']) }}"></div>
                <div class="col-md-2"><label class="form-label">Formato de data *</label><select name="date_format" class="form-select"><option value="d/m/Y" @selected(old('date_format', $settings['date_format']) === 'd/m/Y')>31/12/2026</option><option value="Y-m-d" @selected(old('date_format', $settings['date_format']) === 'Y-m-d')>2026-12-31</option></select></div>
                <div class="col-md-2"><label class="form-label">Fuso horário *</label><select name="timezone" class="form-select"><option value="America/Fortaleza" @selected(old('timezone', $settings['timezone']) === 'America/Fortaleza')>Brasília</option><option value="America/Sao_Paulo" @selected(old('timezone', $settings['timezone']) === 'America/Sao_Paulo')>São Paulo</option></select></div>
                <div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo_file" class="form-control" accept="image/*"><small class="text-muted">PNG, JPG, SVG ou WEBP. Máximo de 2 MB.</small></div>
                <div class="col-md-6"><label class="form-label">Favicon</label><input type="file" name="favicon_file" class="form-control" accept="image/*"><small class="text-muted">Imagem usada na aba do navegador. Máximo de 1 MB.</small></div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-palette me-2"></i>Cores da interface</strong></div>
            <div class="card-body row g-3">
                @foreach (['brand_primary_color' => 'Primária', 'brand_secondary_color' => 'Secundária', 'brand_success_color' => 'Sucesso', 'brand_danger_color' => 'Perigo', 'brand_warning_color' => 'Atenção', 'brand_info_color' => 'Informação', 'brand_sidebar_color' => 'Menu lateral'] as $key => $label)
                    <div class="col-6 col-md-3"><label class="form-label">{{ $label }}</label><input type="color" name="{{ $key }}" class="form-control form-control-color w-100" value="{{ old($key, $settings[$key]) }}" title="{{ $label }}"></div>
                @endforeach
                @foreach (['button_primary_color' => 'Botão primário', 'button_secondary_color' => 'Botão secundário', 'button_success_color' => 'Botão sucesso', 'button_danger_color' => 'Botão perigo', 'button_warning_color' => 'Botão atenção', 'button_info_color' => 'Botão informação'] as $key => $label)
                    <div class="col-6 col-md-3"><label class="form-label">{{ $label }}</label><input type="color" name="{{ $key }}" class="form-control form-control-color w-100" value="{{ old($key, $settings[$key]) }}" title="{{ $label }}"></div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-grid-3x3-gap me-2"></i>Ícones dos menus</strong></div>
            <div class="card-body row g-3">
                @foreach (['icon_dashboard' => 'Dashboard', 'icon_students' => 'Alunos', 'icon_teachers' => 'Instrutores', 'icon_employees' => 'Funcionários', 'icon_units' => 'Unidades', 'icon_plans' => 'Planos', 'icon_enrollments' => 'Matrículas', 'icon_financial' => 'Financeiro', 'icon_communication' => 'Comunicação', 'icon_announcements' => 'Comunicados', 'icon_messages' => 'Mensagens', 'icon_reports' => 'Relatórios', 'icon_attendance' => 'Presença', 'icon_exercises' => 'Exercícios', 'icon_workout_plans' => 'Fichas de treino', 'icon_assessments' => 'Avaliações físicas', 'icon_profile' => 'Perfil'] as $key => $label)
                    <div class="col-md-4"><label class="form-label"><i class="{{ old($key, $settings[$key]) }} me-1"></i>{{ $label }}</label><input name="{{ $key }}" class="form-control" required pattern="bi bi-[a-z0-9-]+" value="{{ old($key, $settings[$key]) }}"><small class="text-muted">Bootstrap Icons, por exemplo: bi bi-{{ $key === 'icon_dashboard' ? 'speedometer2' : 'star' }}</small></div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-bell me-2"></i>Notificações</strong></div>
            <div class="card-body row">
                @foreach (['notifications_mail_enabled' => 'E-mail', 'notifications_whatsapp_enabled' => 'WhatsApp', 'notifications_push_enabled' => 'Push no navegador'] as $key => $label)
                    <div class="col-md-4"><div class="form-check form-switch"><input type="hidden" name="{{ $key }}" value="0"><input class="form-check-input" type="checkbox" name="{{ $key }}" value="1" @checked(old($key, $settings[$key]) === '1')><label class="form-check-label">{{ $label }}</label></div></div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-success mb-4"><i class="bi bi-check-lg me-1"></i>Salvar configurações</button>
    </form>
@stop
