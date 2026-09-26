<section>
    <header class="mb-4">
        <h2 class="h3 mb-2">Alterar senha</h2>
        <p class="text-muted mb-0">Utilize uma senha longa e aleatória para manter sua conta segura.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="password-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Senha atual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
            @if ($errors->updatePassword->has('current_password'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('current_password') }}</div>@endif
        </div>
        <div class="mb-3">
            <label for="update_password_password" class="form-label">Nova senha</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" required>
            @if ($errors->updatePassword->has('password'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password') }}</div>@endif
        </div>
        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label">Confirmar senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
            @if ($errors->updatePassword->has('password_confirmation'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password_confirmation') }}</div>@endif
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Salvar</button>
        @if (session('status') === 'password-updated')<span class="text-success ms-2">Senha atualizada.</span>@endif
    </form>
</section>
