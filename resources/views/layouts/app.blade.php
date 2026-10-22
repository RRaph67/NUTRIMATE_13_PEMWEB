<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Nutrimate — katalog makanan lengkap dengan informasi harga, porsi, dan nilai gizi.">
    <title>@yield('title', 'Nutrimate')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ auth()->check() ? route('foods.index') : route('login') }}">
                <span class="brand-mark" aria-hidden="true">🥗</span> Nutrimate
            </a>

            <nav class="nav" aria-label="Navigasi utama">
                @auth
                    <a href="{{ route('foods.index') }}"
                        class="{{ request()->routeIs('foods.*') ? 'is-active' : '' }}"
                        @if (request()->routeIs('foods.*')) aria-current="page" @endif>
                        Makanan
                    </a>
                    <a href="{{ route('profile.show') }}"
                        class="{{ request()->routeIs('profile.*') ? 'is-active' : '' }}"
                        @if (request()->routeIs('profile.*')) aria-current="page" @endif>
                        Profil
                    </a>
                    <form class="inline-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn-logout" type="submit">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="{{ request()->routeIs('login') ? 'is-active' : '' }}"
                        @if (request()->routeIs('login')) aria-current="page" @endif>
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="{{ request()->routeIs('register') ? 'is-active' : '' }}"
                        @if (request()->routeIs('register')) aria-current="page" @endif>
                        Daftar
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main id="main-content" class="site-main" tabindex="-1">
        <div class="container">
            @include('layouts.alerts')
            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Nutrimate — data makanan tersimpan di PostgreSQL (Supabase).</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
