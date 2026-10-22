@extends('layouts.app')

@section('title', 'Profil Saya — Nutrimate')

@section('content')
    <div class="page-header">
        <div>
            <h1>Profil saya</h1>
            <p>Informasi akun dan pengaturan profil Anda.</p>
        </div>
    </div>

    <div class="panel">
        <h2 style="font-size: 1.15rem; margin-bottom: 16px;">Informasi Akun</h2>
        <dl class="profile-list">
            <div>
                <dt>ID user</dt>
                <dd class="mono">{{ $user->id }}</dd>
            </div>
            <div>
                <dt>Nama lengkap</dt>
                <dd>{{ $user->profile?->full_name ?? $user->name }}</dd>
            </div>
            <div>
                <dt>Email</dt>
                <dd>{{ $user->email }}</dd>
            </div>
            <div>
                <dt>Terdaftar sejak</dt>
                <dd>{{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="panel" style="margin-top: 24px;">
        <h2 style="font-size: 1.15rem; margin-bottom: 8px;">Ubah Nama Profil</h2>
        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 20px;">Perbarui nama lengkap yang ditampilkan di profil akun Anda.</p>

        <form action="{{ route('profile.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="name">
                        Nama lengkap <span class="required" aria-hidden="true">*</span>
                    </label>
                    <input id="name" name="name" type="text"
                        value="{{ old('name', $user->profile?->full_name ?? $user->name) }}"
                        autocomplete="name" placeholder="Nama lengkap Anda" required minlength="3" maxlength="100"
                        @error('name') aria-invalid="true" aria-describedby="name_error" @enderror>
                    @error('name')
                        <p class="field-error" id="name_error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="btn-row" style="margin-top: 18px">
                <button type="submit" class="btn btn-primary">Simpan Nama</button>
            </div>
        </form>
    </div>

    <div class="panel" style="margin-top: 24px;">
        <h2 style="font-size: 1.15rem; margin-bottom: 8px;">Ubah Password</h2>
        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 20px;">Pastikan akun Anda menggunakan password yang aman dan sulit ditebak.</p>

        <form action="{{ route('profile.password.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="current_password">
                        Password saat ini <span class="required" aria-hidden="true">*</span>
                    </label>
                    <input id="current_password" name="current_password" type="password"
                        autocomplete="current-password" required
                        @error('current_password') aria-invalid="true" aria-describedby="current_password_error" @enderror>
                    @error('current_password')
                        <p class="field-error" id="current_password_error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">
                        Password baru <span class="required" aria-hidden="true">*</span>
                    </label>
                    <input id="password" name="password" type="password"
                        autocomplete="new-password" required minlength="8"
                        @error('password') aria-invalid="true" aria-describedby="password_error" @enderror>
                    @error('password')
                        <p class="field-error" id="password_error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">
                        Konfirmasi password baru <span class="required" aria-hidden="true">*</span>
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" required minlength="8">
                </div>
            </div>

            <div class="btn-row" style="margin-top: 18px">
                <button type="submit" class="btn btn-primary">Perbarui Password</button>
            </div>
        </form>
    </div>

    <div class="btn-row" style="margin-top: 24px">
        <a class="btn btn-secondary" href="{{ route('foods.index') }}">Lihat katalog makanan</a>
        <form class="inline-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-secondary" type="submit">Keluar</button>
        </form>
    </div>
@endsection
