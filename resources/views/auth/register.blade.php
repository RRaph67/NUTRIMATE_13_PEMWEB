@extends('layouts.app')

@section('title', 'Daftar — Nutrimate')

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <h1 class="auth-title">Buat akun baru</h1>
            <p class="auth-subtitle">Daftar untuk mengakses katalog makanan Nutrimate.</p>

            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label for="full_name">
                            Nama lengkap <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                            autocomplete="name" placeholder="Contoh: Budi Santoso" required
                            @error('full_name') aria-invalid="true" aria-describedby="full_name_error" @enderror>
                        @error('full_name')
                            <p class="field-error" id="full_name_error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">
                            Email <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            autocomplete="email" placeholder="nama@email.com" required
                            @error('email') aria-invalid="true" aria-describedby="email_error" @enderror>
                        @error('email')
                            <p class="field-error" id="email_error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">
                            Password <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                            required
                            @error('password') aria-invalid="true"
                                aria-describedby="password_hint password_error"
                            @else aria-describedby="password_hint" @enderror>
                        <p class="hint" id="password_hint">Minimal 8 karakter.</p>
                        @error('password')
                            <p class="field-error" id="password_error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">
                            Konfirmasi password <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" required
                            @error('password_confirmation') aria-invalid="true"
                                aria-describedby="password_confirmation_error"
                            @enderror>
                        @error('password_confirmation')
                            <p class="field-error" id="password_confirmation_error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="btn-row" style="margin-top: 22px">
                    <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                </div>
            </form>

            <p class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </div>
    </div>
@endsection
