@extends('layouts.app')

@section('title', 'Masuk — Nutrimate')

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <h1 class="auth-title">Masuk ke akun</h1>
            <p class="auth-subtitle">Gunakan email dan password Anda untuk melanjutkan.</p>

            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                <div class="form-grid">
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
                        <input id="password" name="password" type="password" autocomplete="current-password"
                            required
                            @error('password') aria-invalid="true" aria-describedby="password_error" @enderror>
                        @error('password')
                            <p class="field-error" id="password_error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="btn-row" style="margin-top: 22px">
                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                </div>
            </form>

            <p class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </p>
        </div>
    </div>
@endsection
