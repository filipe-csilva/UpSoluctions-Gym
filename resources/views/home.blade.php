@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <div>
        <h1>Bem-vindo, {{ $user->name }}!</h1>
        <p class="text-muted mb-0">Acesse rapidamente as principais áreas do GymControl.</p>
    </div>
@stop

@section('content')
    <div class="row g-4">
        @if (in_array($role, ['admin', 'manager'], true))
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <div class="card h-100 gym-dashboard-panel">
                        <div class="card-body"><i class="bi bi-speedometer2 fs-2 text-primary"></i><h2 class="h5 mt-3">Dashboard administrativo</h2><p class="text-muted mb-0">Indicadores, evolução e saúde financeira.</p></div>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('announcements.index') }}" class="text-decoration-none">
                <div class="card h-100 gym-dashboard-panel"><div class="card-body"><i class="bi bi-megaphone fs-2 text-warning"></i><h2 class="h5 mt-3">Comunicados</h2><p class="text-muted mb-0">Veja as novidades e avisos da academia.</p></div></div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('messages.index') }}" class="text-decoration-none">
                <div class="card h-100 gym-dashboard-panel"><div class="card-body"><i class="bi bi-chat-dots fs-2 text-info"></i><h2 class="h5 mt-3">Mensagens</h2><p class="text-muted mb-0">Acompanhe suas conversas e respostas.</p></div></div>
            </a>
        </div>
        @if ($role === 'student')
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('student-financial.index') }}" class="text-decoration-none">
                    <div class="card h-100 gym-dashboard-panel"><div class="card-body"><i class="bi bi-wallet2 fs-2 text-success"></i><h2 class="h5 mt-3">Meu financeiro</h2><p class="text-muted mb-0">Consulte mensalidades e vencimentos.</p></div></div>
                </a>
            </div>
        @endif
    </div>
@stop
