@php
    $currentUser = auth()->user();
    $unreadMessageQuery = \App\Models\Message::query()
        ->with(['sender', 'parent'])
        ->visibleTo($currentUser)
        ->where('sender_id', '!=', $currentUser->id)
        ->whereDoesntHave('reads', fn ($query) => $query->where('user_id', $currentUser->id));
    $unreadMessageCount = (clone $unreadMessageQuery)->count();
    $userMessages = $unreadMessageQuery->latest()->limit(5)->get();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Mensagens">
        <i class="bi bi-chat-text" aria-hidden="true"></i>
        @if ($unreadMessageCount > 0)
            <span class="navbar-badge badge text-bg-danger">{{ $unreadMessageCount > 99 ? '99+' : $unreadMessageCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-item dropdown-header">{{ $unreadMessageCount }} mensagem(ns) não lida(s)</span>
        <div class="dropdown-divider"></div>
        @forelse ($userMessages as $message)
            @php($thread = $message->parent ?: $message)
            <a href="{{ route('messages.show', $thread) }}" class="dropdown-item">
                <i class="bi bi-chat-left-text text-primary me-2"></i>
                {{ $message->parent_id ? 'Resposta recebida: ' : '' }}{{ $thread->subject }}
                <small class="d-block text-secondary ms-4">De: {{ $message->sender?->name ?? '-' }}</small>
                <span class="float-end text-secondary fs-7">{{ $message->created_at?->format('d/m H:i') }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <span class="dropdown-item text-secondary">Nenhuma mensagem nova.</span>
        @endforelse
        <a href="{{ route('messages.index') }}" class="dropdown-item dropdown-footer">Ver mensagens</a>
    </div>
</li>
