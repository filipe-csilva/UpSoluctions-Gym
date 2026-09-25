@php
    $accountActivities = \App\Models\ActivityLog::query()
        ->where('user_id', auth()->id())
        ->latest()
        ->limit(5)
        ->get();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Atividades da conta">
        <i class="bi bi-chat-text" aria-hidden="true"></i>
        @if ($accountActivities->count() > 0)<span class="navbar-badge badge text-bg-danger">{{ $accountActivities->count() }}</span>@endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-item dropdown-header">Atividades da conta</span>
        <div class="dropdown-divider"></div>
        @forelse ($accountActivities as $activity)
            <a href="{{ route('profile.edit') }}#sessions" class="dropdown-item">
                <i class="bi {{ $activity->dashboardIcon() }} text-{{ $activity->dashboardColor() }} me-2"></i>
                {{ $activity->description ?: ucfirst($activity->action) }}
                <span class="float-end text-secondary fs-7">{{ $activity->created_at?->format('d/m H:i') }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <span class="dropdown-item text-secondary">Nenhuma atividade registrada.</span>
        @endforelse
        <a href="{{ route('profile.edit') }}#sessions" class="dropdown-item dropdown-footer">Ver atividades</a>
    </div>
</li>
