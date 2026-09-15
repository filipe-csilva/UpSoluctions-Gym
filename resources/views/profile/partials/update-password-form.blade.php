<section>
    <header class="mb-4">
        <h2 class="h3 mb-2">Update Password</h2>
        <p class="text-muted mb-0">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="password-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
            @if ($errors->updatePassword->has('current_password'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('current_password') }}</div>@endif
        </div>
        <div class="mb-3">
            <label for="update_password_password" class="form-label">New Password</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" required>
            @if ($errors->updatePassword->has('password'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password') }}</div>@endif
        </div>
        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
            @if ($errors->updatePassword->has('password_confirmation'))<div class="invalid-feedback d-block">{{ $errors->updatePassword->first('password_confirmation') }}</div>@endif
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Save</button>
        @if (session('status') === 'password-updated')<span class="text-success ms-2">Senha atualizada.</span>@endif
    </form>
</section>
