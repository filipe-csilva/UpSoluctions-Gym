@props(['message' => null, 'type' => null])

@php
    $flashAlerts = [
        'success' => session('success'),
        'danger' => session('error'),
        'warning' => session('warning'),
        'info' => session('status'),
    ];
    $isLoginError = request()->routeIs('login') && $errors->has('email');
    $isInactiveLogin = $isLoginError && $errors->first('email') === 'Usuário inativo. Entre em contato com o administrador.';
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

    .app-alert-login {
        display: flex;
        align-items: center;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        font-size: .85rem;
        overflow: hidden;
        white-space: nowrap;
    }

    @media (max-width: 575.98px) {
        .app-alert-login {
            width: calc(100vw - 2rem);
            font-size: .72rem;
        }
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
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        <div class="alert alert-{{ $isInactiveLogin ? 'warning' : 'danger' }} alert-dismissible fade show app-alert{{ $isLoginError ? ' app-alert-login' : '' }}" role="alert" data-auto-dismiss>
            <i class="bi bi-{{ $isInactiveLogin ? 'exclamation-triangle' : 'exclamation-circle' }} me-2"></i>
            @if ($isLoginError)
                {{ $isInactiveLogin ? $errors->first('email') : 'O E-mail ou a senha está inválido!' }}
            @else
                <strong>Verifique os campos informados.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
        window.setTimeout(() => {
            alert.classList.add('is-leaving');
            window.setTimeout(() => alert.remove(), 300);
        }, 7000);
    });
</script>
