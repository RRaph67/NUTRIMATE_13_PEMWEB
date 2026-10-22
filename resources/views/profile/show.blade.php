@extends('layouts.app')

@section('title', 'Profil — NutriMate')

@section('content')
@php
    $name    = $user->profile?->full_name ?? $user->name;
    $email   = $user->email;
    $initial = mb_strtoupper(mb_substr(trim($name) !== '' ? trim($name) : 'N', 0, 1));

    $eyeSvg  = '<svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path class="slash" d="M4 4l16 16"/></svg>';
    $lockSvg = '<svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>';
@endphp

<div class="pf-container">
    {{-- 1. Kartu Identitas Pengguna (Atas) --}}
    <section class="pf-card pf-user-card" aria-label="Informasi Pengguna">
        <div class="pf-avatar">{{ $initial }}</div>
        <div class="pf-user-info">
            <span class="pf-name">{{ $name }}</span>
            <span class="pf-email">{{ $email }}</span>
        </div>
    </section>

    {{-- Grid Form Horizontal (Kiri: Data Diri, Kanan: Keamanan) --}}
    <div class="pf-form-grid">
        {{-- 2. Kartu Data Diri (Kiri) --}}
        <section class="pf-card" id="data-diri">
            <div class="pf-card-head">
            <h2>Data Diri</h2>
            <p>Kamu bisa mengubah nama, sedangkan email terkunci.</p>
        </div>

        @if ($errors->has('name'))
            <div class="form-error" role="alert">{{ $errors->first('name') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" novalidate>
            @csrf
            @method('PUT')

            <div class="pf-fields">
                <label class="field" for="name">
                    <span>Nama Lengkap</span>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $name) }}"
                        maxlength="60" autocomplete="name" required>
                </label>

                <label class="field" for="email_readonly">
                    <span>Email</span>
                    <div class="lock-wrap">
                        <input type="email" id="email_readonly"
                            value="{{ $email }}"
                            placeholder="Email belum tersedia"
                            readonly aria-readonly="true" tabindex="-1">
                        <i class="lock-ic" aria-hidden="true">{!! $lockSvg !!}</i>
                    </div>
                    <small class="hint">Email tidak dapat diubah.</small>
                </label>
            </div>

            <div class="pf-card-footer">
                <button type="submit" class="btn-auth btn-auth--full">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    {{-- 3. Kartu Keamanan (Bawah) --}}
    <section class="pf-card" id="keamanan">
        <div class="pf-card-head">
            <h2>Keamanan</h2>
            <p>Ganti password untuk menjaga akunmu tetap aman.</p>
        </div>

        @if ($errors->has('current_password') || $errors->has('password'))
            <div class="form-error" role="alert">
                {{ $errors->first('current_password') ?: $errors->first('password') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}" novalidate>
            @csrf
            @method('PUT')

            <div class="pf-fields">
                <label class="field" for="current_password">
                    <span>Password Lama</span>
                    <div class="pw-wrap">
                        <input type="password" name="current_password" id="current_password"
                            placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            {!! $eyeSvg !!}
                        </button>
                    </div>
                </label>

                <label class="field" for="password">
                    <span>Password Baru</span>
                    <div class="pw-wrap">
                        <input type="password" name="password" id="password"
                            placeholder="Minimal 8 karakter" autocomplete="new-password"
                            minlength="8" required>
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            {!! $eyeSvg !!}
                        </button>
                    </div>
                </label>

                <label class="field" for="password_confirmation">
                    <span>Konfirmasi Password Baru</span>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Ulangi password baru" autocomplete="new-password"
                            minlength="8" required>
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            {!! $eyeSvg !!}
                        </button>
                    </div>
                </label>
            </div>

            <div class="pf-card-footer">
                <button type="submit" class="btn-auth btn-auth--full">Ganti Password</button>
            </div>
        </form>
    </section>
    </div>

    {{-- Tombol Keluar horizontal memanjang di paling bawah --}}
    <form action="{{ route('logout') }}" method="POST" class="pf-logout-container">
        @csrf
        <button type="submit" class="btn-logout-full">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 4H5v16h4M16 8l4 4-4 4M20 12H9"/></svg>
            Keluar
        </button>
    </form>
</div>
@endsection

