@php
    $isLoggedIn = auth()->check();
    $userName = $isLoggedIn ? (auth()->user()->profile?->full_name ?? auth()->user()->name) : '';
    $initials  = $isLoggedIn ? mb_strtoupper(mb_substr($userName, 0, 1)) : '';

    $nav = [
        ['Home', route('home'), request()->routeIs('home')],
        ['Food Database', $isLoggedIn ? route('foods.index') : '#', request()->routeIs('foods.*')],
        ['Recommendation', '#', false],
        ['Calculate Nutritions', '#', false],
    ];
@endphp
<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar" aria-hidden="true">
    <div class="side-brand">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="" class="brand-logo" width="28" height="28">
        <div>
            <div class="brand-name">Nutri<b>Mate</b></div>
            <div class="tagline">Gizi terjaga, Hemat Terasa</div>
        </div>
    </div>

    @if ($isLoggedIn)
        {{-- Profile row in sidebar --}}
        <a href="{{ route('profile.show') }}"
           class="side-profile {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="side-avatar">{{ $initials }}</span>
            <span class="side-profile-info">
                <span class="side-profile-name">{{ $userName }}</span>
                <span class="side-profile-sub">Lihat profil</span>
            </span>
            <svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
        </a>
    @endif

    <div class="side-label">General</div>
    <nav class="side-nav">
        @foreach ($nav as [$label, $href, $active])
            <a href="{{ $href }}"
               class="side-item {{ $active ? 'active' : '' }}"
               @if(!$isLoggedIn && $href === '#') onclick="event.preventDefault();" @endif>
                <span>{{ $label }}</span>
                <svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
            </a>
        @endforeach
    </nav>

    <div class="side-label">Akun</div>
    <nav class="side-nav">
        @if ($isLoggedIn)
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
                @csrf
                <button type="submit" class="side-item logout" style="width: 100%; border: none; background: transparent; cursor: pointer; font-family: inherit; text-align: left;">
                    <span>Keluar</span>
                    <svg class="out" viewBox="0 0 24 24"><path d="M9 4H5v16h4M16 8l4 4-4 4M20 12H9"/></svg>
                </button>
            </form>
        @else
            <button type="button" class="side-item" data-open-modal="register" style="width: 100%; border: none; background: transparent; cursor: pointer; font-family: inherit; text-align: left;">
                <span>Daftar</span>
                <svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
            </button>
            <button type="button" class="side-item" data-open-modal="login" style="width: 100%; border: none; background: transparent; cursor: pointer; font-family: inherit; text-align: left;">
                <span>Masuk</span>
                <svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
            </button>
        @endif
    </nav>
</aside>
