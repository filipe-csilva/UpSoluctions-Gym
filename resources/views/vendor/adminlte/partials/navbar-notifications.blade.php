@php
    $currentUser = auth()->user();
    $unreadNotifications = $currentUser?->unreadNotifications()->latest()->limit(5)->get() ?? collect();
    $unreadNotificationCount = $currentUser?->unreadNotifications()->count() ?? 0;
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Notificações">
        <i class="bi bi-bell-fill" aria-hidden="true"></i>
        @if ($unreadNotificationCount > 0)
            <span class="navbar-badge badge text-bg-warning">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-item dropdown-header">{{ $unreadNotificationCount }} notificação(ões) não lida(s)</span>
        <div class="dropdown-divider"></div>
        @forelse ($unreadNotifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item">
                <i class="bi bi-bell text-warning me-2"></i>
                {{ $notification->data['title'] ?? 'Nova notificação' }}
                <small class="d-block text-secondary ms-4">{{ $notification->data['message'] ?? '' }}</small>
                <span class="float-end text-secondary fs-7">{{ $notification->created_at?->format('d/m H:i') }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <span class="dropdown-item text-secondary">Nenhuma notificação nova.</span>
        @endforelse
    </div>
</li>
