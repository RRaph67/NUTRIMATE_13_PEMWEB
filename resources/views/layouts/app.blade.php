<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="NutriMate — Gizi terjaga, Hemat Terasa. Katalog makanan lengkap dengan informasi harga, porsi, dan nilai gizi.">
    <title>@yield('title', 'NutriMate')</title>

    <!-- Google Font: Poppins (rules.md §4.1) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>

    <!-- Navbar global reusable -->
    @include('partials.navbar')

    <!-- Sidebar mobile & tablet reusable -->
    @include('partials.sidebar')

    <main id="main-content" class="site-main" tabindex="-1">
        <div class="container">
            @include('layouts.alerts')
            @yield('content')
        </div>
    </main>

    <!-- Footer global reusable -->
    @include('partials.footer')

    @stack('scripts')
</body>

</html>
