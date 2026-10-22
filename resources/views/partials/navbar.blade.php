@php
    $isLoggedIn = auth()->check();
    $homeUrl = route('home');
    $userName = $isLoggedIn ? (auth()->user()->profile?->full_name ?? auth()->user()->name) : '';
    $userInitial = $isLoggedIn ? mb_strtoupper(mb_substr(trim($userName) !== '' ? trim($userName) : 'U', 0, 1)) : '';

    $nav = [
        ['Home', route('home'), request()->routeIs('home')],
        ['Food Database', $isLoggedIn ? route('foods.index') : '#', request()->routeIs('foods.*')],
        ['Recommendation', '#', false],
        ['Calculate Nutritions', '#', false],
    ];
@endphp
<header class="navbar" id="navbar">
    <div class="container nav-inner">
        <a href="{{ $homeUrl }}" class="brand">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="" class="brand-logo" width="30" height="30">
            <span class="brand-name">Nutri<b>Mate</b></span>
        </a>

        <!-- Menu desktop (tampil mulai layar >= 1024px) -->
        <nav class="nav-pill" aria-label="Menu utama">
            @foreach ($nav as [$label, $href, $active])
                <a href="{{ $href }}"
                   class="{{ $active ? 'active' : '' }}"
                   @if(!$isLoggedIn && $href === '#') onclick="event.preventDefault();" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <!-- Tombol akun desktop (paling kanan) -->
        <div class="auth">
            @if ($isLoggedIn)
                <a href="{{ route('profile.show') }}"
                   class="nav-avatar {{ request()->routeIs('profile.*') ? 'nav-avatar--active' : '' }}"
                   title="Profil Saya ({{ $userName }})"
                   aria-label="Profil Saya">
                    {{ $userInitial }}
                </a>
            @else
                <button type="button" class="btn-sm btn-outline" data-open-modal="login">Masuk</button>
                <button type="button" class="btn-sm btn-fill" data-open-modal="register">Daftar</button>
            @endif
        </div>

        <!-- Tombol menu mobile & tablet (sembunyi di desktop) -->
        <button class="menu-btn" id="menuBtn" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>
