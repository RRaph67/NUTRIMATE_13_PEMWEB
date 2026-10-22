@extends('layouts.app')

@section('title', 'NutriMate — Makan Sehat, Kantong Aman')

@section('content')
@php
    $icons = [
        'food'   => '<path d="M4 10h16l-1.5 9h-13L4 10zM3 7l2-3h14l2 3v3H3V7z"/>',
        'budget' => '<path d="M19 12c0-3-3-5-7-5S5 9 5 12c0 2 1 3.5 2.5 4.3V19h3v-1.5h3V19h3v-2.7c1.5-.8 2.5-2.3 2.5-4.3z"/><path d="M19 11h2v3h-2M9 11h.01"/>',
        'plan'   => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>',
        'calc'   => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h3M9.5 5.5v3M14 7h2M8 13h3M14 13h2M14 17h2M8.5 15v4"/>',
    ];
@endphp

    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-text">
                <span class="badge">
                    <svg viewBox="0 0 24 24"><path d="M12 21v-9M12 12c0-4 3-6 7-6 0 4-3 6-7 6zM12 14c0-3-2-5-6-5 0 3 2 5 6 5z"/></svg>
                    Solusi Cerdas Mahasiswa
                </span>
                <h1>Makan Sehat,<br><span>Kantong Aman.</span></h1>
                <p>Aplikasi pengatur pola makan harian mahasiswa kost untuk capai target nutrisi harian optimal tanpa bikin dompet jebol di akhir bulan.</p>
                <a href="#fitur" class="btn-main">Mulai Sekarang
                    <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="hero-visual">
                <div class="photo-card">
                    <img src="{{ asset('assets/img/hero.svg') }}" alt="Meal prep makanan sehat NutriMate">
                </div>
                <div class="float-info">
                    <span class="float-icon">
                        <svg viewBox="0 0 24 24"><path d="M6 3l12 12M18 3L6 15M5 20l4-4M15 21c3-1 5-3 4-6"/></svg>
                    </span>
                    <div class="float-text">
                        <strong><i class="dot"></i>Target Gizi Harian: 2.100 kkal</strong>
                        <span>Estimasi Pengeluaran: <b>Rp 22.500/hari</b></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="fitur">
        <div class="container">
            <div class="section-head">
                <span class="badge badge-sm">Fitur Pilihan</span>
                <h2>Fitur Utama NutriMate</h2>
                <p>Solusi terintegrasi yang memudahkan anak kost makan bergizi hemat setiap hari.</p>
            </div>

            <div class="feature-grid">
                @foreach ($features as $f)
                    <article class="card reveal">
                        <div class="icon-box {{ $f['color'] }}">
                            <svg viewBox="0 0 24 24">{!! $icons[$f['icon']] !!}</svg>
                        </div>
                        <h3>{{ $f['title'] }}</h3>
                        <p>{{ $f['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta-wrap">
        <div class="cta reveal">
            <span class="badge-light">Mulai Hemat &amp; Sehat</span>
            <h2>Siap Atur Budget Makanmu?</h2>
            <p>Gabung bersama ribuan mahasiswa lainnya dan mulai merencanakan nutrisi dengan dompet harianmu.</p>
            @auth
                <a href="{{ route('foods.index') }}" class="btn-yellow">Lihat Katalog Makanan</a>
            @else
                <a href="{{ route('register') }}" class="btn-yellow" data-open-modal="register">Daftar Sekarang</a>
            @endauth
        </div>
    </section>

    @include('partials.auth-modal')
@endsection
