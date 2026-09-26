@extends('adminlte::page')

@section('title', $message->subject)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center"><h1>{{ $message->subject }}</h1><a href="{{ route('messages.index') }}" class="btn btn-secondary">Voltar</a></div>
@stop

@section('content')
    <div class="card"><div class="card-body">
        <p class="text-muted mb-1">De: {{ $message->sender?->name ?? '-' }}</p>
        <p class="text-muted">{{ $message->created_at?->format('d/m/Y H:i') }} · {{ $message->unit?->name ?? ucfirst($message->audience) }}</p>
        <div class="border-top pt-3">{!! nl2br(e($message->body)) !!}</div>
        @if ($message->reads->isNotEmpty())
            <div class="alert alert-success mt-4 mb-0">Lida por você em {{ $message->reads->first()->read_at->format('d/m/Y H:i') }}.</div>
        @endif
        @if ($message->replies->isNotEmpty())
            <h2 class="h5 border-top mt-4 pt-3">Respostas</h2>
            @foreach ($message->replies as $reply)
                <div class="border-top py-3">
                    <div class="d-flex justify-content-between"><strong>{{ $reply->sender?->name ?? '-' }}</strong><small class="text-muted">{{ $reply->created_at?->format('d/m/Y H:i') }}</small></div>
                    <div class="mt-2">{!! nl2br(e($reply->body)) !!}</div>
                    @if ($reply->reads->isNotEmpty())<small class="text-success">Lida por você em {{ $reply->reads->first()->read_at->format('d/m/Y H:i') }}</small>@endif
                </div>
            @endforeach
        @endif
        <div class="border-top mt-4 pt-3">
            <h2 class="h5">Responder</h2>
            <form method="POST" action="{{ route('messages.reply', $message) }}">
                @csrf
                <textarea name="body" class="form-control" rows="4" required placeholder="Digite sua resposta"></textarea>
                <button class="btn btn-primary mt-3" type="submit">Enviar resposta</button>
            </form>
        </div>
    </div></div>
@stop
