@php
    $navLeft = app('adminlte')->menu('navbar-left');
@endphp
<style>
    :root {
        --gym-primary: {{ $systemSettings['brand_primary_color'] ?? '#0d6efd' }};
        --gym-sidebar: {{ $systemSettings['brand_sidebar_color'] ?? '#343a40' }};
        --gym-button-primary: {{ $systemSettings['button_primary_color'] ?? '#0d6efd' }};
        --gym-button-secondary: {{ $systemSettings['button_secondary_color'] ?? '#6c757d' }};
        --gym-button-success: {{ $systemSettings['button_success_color'] ?? '#198754' }};
        --gym-button-danger: {{ $systemSettings['button_danger_color'] ?? '#dc3545' }};
        --gym-button-warning: {{ $systemSettings['button_warning_color'] ?? '#ffc107' }};
        --gym-button-info: {{ $systemSettings['button_info_color'] ?? '#0dcaf0' }};
    }
    .app-sidebar { --lte-sidebar-bg: var(--gym-sidebar); }
    .btn-primary { background-color: var(--gym-button-primary); border-color: var(--gym-button-primary); }
    .btn-secondary { background-color: var(--gym-button-secondary); border-color: var(--gym-button-secondary); }
    .btn-success { background-color: var(--gym-button-success); border-color: var(--gym-button-success); }
    .btn-danger { background-color: var(--gym-button-danger); border-color: var(--gym-button-danger); }
    .btn-warning { background-color: var(--gym-button-warning); border-color: var(--gym-button-warning); }
    .btn-info { background-color: var(--gym-button-info); border-color: var(--gym-button-info); }
</style>
<nav class="app-header {{ config('adminlte.classes_topnav', 'navbar-expand bg-body') }} navbar">
    <div class="{{ config('adminlte.classes_topnav_container', 'container-fluid') }}">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="{{ __('Toggle sidebar') }}">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('panel') }}" class="nav-link">
                    <i class="bi bi-grid-1x2 me-1" aria-hidden="true"></i> {{ __('adminlte.home') }}
                </a>
            </li>
            @if (config('adminlte.sidebar_docs_url'))
                <li class="nav-item d-none d-md-block">
                    <a href="{{ config('adminlte.sidebar_docs_url') }}" class="nav-link" target="_blank" rel="noopener">
                        <i class="bi bi-book me-1" aria-hidden="true"></i> {{ __('adminlte.documentation') }}
                    </a>
                </li>
            @endif
            @foreach ($navLeft as $item)
                <li class="nav-item d-none d-md-block">
                    <a href="{{ $item['href'] ?? '#' }}" class="nav-link">{{ $item['text'] ?? '' }}</a>
                </li>
            @endforeach
        </ul>

        <ul class="navbar-nav ms-auto">
            @include('adminlte::partials.navbar-messages')
            @include('adminlte::partials.navbar-notifications')

            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="{{ __('Toggle fullscreen') }}">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none" aria-hidden="true"></i>
                </a>
            </li>

            @if (config('adminlte.color_mode_toggle', true))
                @include('adminlte::partials.color-mode')
            @endif
            @if (config('adminlte.control_sidebar', false))
                <li class="nav-item">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="offcanvas"
                       data-bs-target="#adminlte-control-sidebar" aria-controls="adminlte-control-sidebar"
                       aria-label="{{ __('Toggle settings panel') }}">
                        <i class="bi bi-gear-fill" aria-hidden="true"></i>
                    </a>
                </li>
            @endif
            @if (config('adminlte.usermenu_enabled', true))
                @include('adminlte::partials.usermenu')
            @endif
        </ul>
    </div>
</nav>

@include('adminlte::partials.command-palette')
