@extends('adminlte::page')

@section('title', 'Mensagens')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mensagens</h1>
        <a href="{{ route('messages.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nova mensagem</a>
    </div>
@stop

@section('content')
    <x-alerts />
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Assunto</th><th>De</th><th>Destino</th><th>Status</th><th>Data</th><th></th></tr></thead>
                <tbody>
                    @forelse ($messages as $message)
                        @php($isRead = $message->reads->isNotEmpty() || $message->sender_id === auth()->id())
                        <tr>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->sender?->name ?? '-' }}</td>
                            <td>{{ $message->recipient?->name ?? ($message->unit?->name ?? ucfirst($message->audience)) }}</td>
                            <td><span class="status-pill status-pill-{{ $isRead ? 'success' : 'warning' }}">{{ $isRead ? 'Lida' : 'Não lida' }}</span></td>
                            <td>{{ $message->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end"><a href="{{ route('messages.show', $message) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Visualizar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Nenhuma mensagem disponível.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">{{ $messages->links() }}</div>
    </div>
@stop
