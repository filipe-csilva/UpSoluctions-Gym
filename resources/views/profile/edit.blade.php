@extends('adminlte::page')
@section('title', 'Profile')
@section('content_header') <h1>Profile</h1> @stop
@section('content')
    <x-alerts />
    <div class="row g-4">
        <div class="col-12 col-xl-4"><div class="card h-100"><div class="card-body text-center py-4">
            @if ($user->profile_photo_url)
                <img src="{{ $user->profile_photo_url }}" class="profile-avatar mx-auto mb-3 object-fit-cover" alt="Avatar de {{ $user->name }}">
            @else
                <div class="profile-avatar {{ $user->profileAvatarGenderClass() }} mx-auto mb-3">{{ $user->profileAvatarInitials() }}</div>
            @endif
            <h2 class="h4 mb-1">{{ $user->name }}</h2><p class="text-muted mb-4">{{ $user->email }}</p>
            <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
                @csrf
                <label for="avatar" class="form-label text-start d-block">Avatar</label>
                <input id="avatar" name="avatar" type="file" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div class="d-grid mt-2"><button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Upload avatar</button></div>
            </form>
        </div></div></div>
        <div class="col-12 col-xl-8"><div class="card h-100">
            <div class="card-header p-0"><ul class="nav nav-tabs card-header-tabs px-3" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab">Change password</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#sessions" type="button" role="tab">Sessions</button></li>
            </ul></div>
            <div class="card-body tab-content">
                <section id="change-password" class="tab-pane fade show active" role="tabpanel">@include('profile.partials.update-password-form')</section>
                <section id="sessions" class="tab-pane fade profile-session-list" role="tabpanel">
                    <h2 class="h5">Atividades do usuário</h2>
                    @forelse ($activities as $activity)
                        <div class="d-flex align-items-start gap-3 border-bottom py-3">
                            <span class="text-{{ $activity->dashboardColor() }}"><i class="bi {{ $activity->dashboardIcon() }}"></i></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="fw-semibold">{{ $activity->description ?: str_replace('_', ' ', ucfirst($activity->action)) }}</div>
                                    <small class="text-muted text-nowrap">{{ $activity->created_at?->format('d/m/Y H:i') }}</small>
                                </div>
                                <small class="text-muted d-block">{{ $activity->deviceType() }} · {{ $activity->browserName() }} · IP: {{ $activity->ip_address ?: 'Não informado' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhuma atividade registrada.</p>
                    @endforelse
                </section>
            </div>
        </div></div>
    </div>
@stop
