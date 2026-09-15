@php
    $user = auth()->user();
    $name = $user->name ?? ($user->email ?? 'Guest');
    $hasAvatar = ! empty($user?->profile_photo_url);
    $avatar = $hasAvatar ? $user->profile_photo_url : null;
    $memberSince = $user?->created_at ? $user->created_at->format('M. Y') : null;
    $showImage = (bool) config('adminlte.usermenu_image');
@endphp
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        @if ($hasAvatar)
            <img src="{{ $avatar }}" class="user-image rounded-circle shadow" alt="{{ $name }}" width="30" height="30">
        @else
            <span class="user-image gym-user-avatar {{ $user->profileAvatarGenderClass() }}" aria-label="{{ $name }}">{{ $user->profileAvatarInitials() }}</span>
        @endif
        <span class="d-none d-md-inline">{{ $name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        @if (config('adminlte.usermenu_header'))
            <li class="user-header {{ config('adminlte.usermenu_header_class', 'text-bg-primary') }}"
                @unless ($showImage) style="min-height: 0" @endunless>
                @if ($showImage)
                    @if ($hasAvatar)
                        <img src="{{ $avatar }}" class="rounded-circle shadow" alt="{{ $name }}" width="90" height="90">
                    @else
                        <span class="profile-avatar {{ $user->profileAvatarGenderClass() }} mx-auto">{{ $user->profileAvatarInitials() }}</span>
                    @endif
                @endif
                <p>
                    {{ $name }}
                    @if (config('adminlte.usermenu_desc') && $memberSince)
                        <small>{{ __('adminlte.member_since') }} {{ $memberSince }}</small>
                    @endif
                </p>
            </li>
        @endif

        {{-- Followers, Sales and Friends remain disabled until these features are implemented. --}}

        <li class="user-footer">
            <a href="#" class="btn btn-outline-danger w-100"
               onclick="event.preventDefault(); document.getElementById('adminlte-logout-form').submit();">
                {{ __('adminlte.sign_out') }}
            </a>
            <form id="adminlte-logout-form" action="{{ url('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</li>
