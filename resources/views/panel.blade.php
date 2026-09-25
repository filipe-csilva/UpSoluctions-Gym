@extends('adminlte::page')

@section('title', 'Notícias')

@section('content_header')
    <div>
        <h1>Notícias e comunicados</h1>
        <p class="text-muted mb-0">Informações importantes da academia.</p>
    </div>
@stop

@section('content')
    <div class="row g-4">
        @forelse ($announcements as $announcement)
            <div class="col-12 col-md-6 {{ $announcements->count() === 1 ? 'col-xl-12' : ($announcements->count() === 2 ? 'col-xl-6' : 'col-xl-4') }}">
                <article class="card h-100 gym-dashboard-panel">
                    <div class="card-header">
                        <span class="gym-panel-icon text-warning"><i class="bi bi-megaphone-fill"></i></span>
                        <div class="gym-panel-heading">
                            <h2 class="card-title">{{ $announcement->title }}</h2>
                            <small>{{ $announcement->unit?->name ?? 'Todas as unidades' }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">{!! nl2br(e($announcement->message)) !!}</p>
                        <small class="text-muted">
                            Publicado em {{ $announcement->start_at?->format('d/m/Y H:i') ?? 'Imediatamente' }}
                        </small>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-megaphone fs-1 d-block mb-3"></i>
                        Nenhuma notícia ou comunicado disponível no momento.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($announcements->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $announcements->links() }}
        </div>
    @endif
@stop
