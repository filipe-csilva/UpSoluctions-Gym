@props(['message' => null, 'type' => null])

@php
    $flashAlerts = [
        'success' => session('success'),
        'danger' => session('error'),
        'warning' => session('warning'),
        'info' => session('status'),
    ];
    $isLoginError = request()->routeIs('login') && $errors->has('email');
    if ($message) {
        $flashAlerts[$type ?: 'info'] = $message;
    }
@endphp

<style>
    .app-alerts {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1080;
        width: min(24rem, calc(100vw - 2rem));
    }

    .app-alert {
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        transition: opacity .3s ease, transform .3s ease;
    }

    .app-alert.is-leaving {
        opacity: 0;
        transform: translateX(120%);
    }
</style>

<div class="app-alerts" aria-live="polite" aria-atomic="true">
    @foreach ($flashAlerts as $type => $message)
        @if ($message)
            <div class="alert alert-{{ $type }} alert-dismissible fade show app-alert" role="alert" data-auto-dismiss>
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'danger' ? 'exclamation-circle' : 'info-circle') }} me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show app-alert" role="alert" data-auto-dismiss>
            <i class="bi bi-exclamation-circle me-2"></i>
            @if ($isLoginError)
                O E-mail ou a senha está inválido!
            @else
                <strong>Verifique os campos informados.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
        window.setTimeout(() => {
            alert.classList.add('is-leaving');
            window.setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
</script>
