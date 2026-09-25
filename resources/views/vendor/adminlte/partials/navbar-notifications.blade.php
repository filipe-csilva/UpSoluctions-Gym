@php
    $currentUser = auth()->user();
    $currentRole = $currentUser?->role?->value;
    $activeAnnouncements = \App\Models\Announcement::query()
        ->where('active', true)
        ->where(function ($query): void {
            $query->whereNull('start_at')->orWhere('start_at', '<=', now());
        })
        ->where(function ($query): void {
            $query->whereNull('end_at')->orWhere('end_at', '>=', now());
        })
        ->where(function ($query) use ($currentUser, $currentRole): void {
            $query->whereNull('unit_id')->orWhere('unit_id', $currentUser?->unit_id);
            $query->where(function ($target) use ($currentRole): void {
                $target->whereNull('target_role')->orWhere('target_role', 'all')->orWhere('target_role', $currentRole);
            });
        })
        ->latest()
        ->limit(5)
        ->get();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Notificações">
        <i class="bi bi-bell-fill" aria-hidden="true"></i>
        @if ($activeAnnouncements->count() > 0)<span class="navbar-badge badge text-bg-warning">{{ $activeAnnouncements->count() }}</span>@endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-item dropdown-header">{{ $activeAnnouncements->count() }} comunicados ativos</span>
        <div class="dropdown-divider"></div>
        @forelse ($activeAnnouncements as $announcement)
            <a href="{{ route('announcements.show', $announcement) }}" class="dropdown-item">
                <i class="bi bi-megaphone-fill text-warning me-2"></i>{{ $announcement->title }}
                <span class="float-end text-secondary fs-7">{{ $announcement->created_at?->format('d/m') }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <span class="dropdown-item text-secondary">Nenhum comunicado ativo.</span>
        @endforelse
        <a href="{{ route('announcements.index') }}" class="dropdown-item dropdown-footer">Ver comunicados</a>
    </div>
</li>
