@php
    $openModal = session('open_modal', '');
    if ($errors->any()) {
        $openModal = old('form', 'login');
    }
    $isLoggedIn = auth()->check();
    $showRegister = ($openModal === 'register');
@endphp

@if (!$isLoggedIn)
<div class="modal {{ $openModal ? 'is-open' : '' }}" id="authModal" aria-hidden="{{ $openModal ? 'false' : 'true' }}">
    <div class="modal-card" role="dialog" aria-modal="true" aria-label="Masuk atau daftar NutriMate">
        <button type="button" class="modal-close" data-close-modal aria-label="Tutup">
            <svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>

        <!-- ================= PANEL MASUK ================= -->
        <section class="auth-panel" data-panel="login" {{ $showRegister ? 'hidden' : '' }}>
            <h2 class="auth-title">Sign In</h2>
            <p class="auth-sub">Masuk untuk mulai hidup sehatmu</p>

            @if ($errors->any() && old('form') === 'login')
                <div class="form-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <input type="hidden" name="form" value="login">

                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" placeholder="nama@exmp.com" autocomplete="email" required
                           value="{{ old('form') === 'login' ? old('email') : '' }}">
                </label>

                <label class="field">
                    <span>Password</span>
                    <div class="pw-wrap">
                        <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path class="slash" d="M4 4l16 16"/>
                            </svg>
                        </button>
                    </div>
                </label>

                <div class="forgot"><a href="#">Lupa Password?</a></div>

                <button type="submit" class="btn-auth">Masuk</button>
            </form>

            <div class="auth-switch">
                Belum punya akun?
                <a href="{{ route('register') }}" data-open-modal="register">Daftar Sekarang</a>
            </div>
        </section>

        <!-- ================= PANEL DAFTAR ================= -->
        <section class="auth-panel" data-panel="register" {{ $showRegister ? '' : 'hidden' }}>
            <h2 class="auth-title">Sign Up</h2>
            <p class="auth-sub">Daftar untuk mulai hidup sehatmu</p>

            @if ($errors->any() && old('form') === 'register')
                <div class="form-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <input type="hidden" name="form" value="register">

                <label class="field">
                    <span>Nama Lengkap</span>
                    <input type="text" name="full_name" placeholder="Nama kamu" autocomplete="name" required
                           value="{{ old('form') === 'register' ? old('full_name') : '' }}">
                </label>

                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" placeholder="nama@exmp.com" autocomplete="email" required
                           value="{{ old('form') === 'register' ? old('email') : '' }}">
                </label>

                <label class="field">
                    <span>Password</span>
                    <div class="pw-wrap">
                        <input type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required minlength="8">
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path class="slash" d="M4 4l16 16"/>
                            </svg>
                        </button>
                    </div>
                </label>

                <label class="field">
                    <span>Konfirmasi Password</span>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" required minlength="8">
                        <button type="button" class="pw-toggle" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path class="slash" d="M4 4l16 16"/>
                            </svg>
                        </button>
                    </div>
                </label>

                <button type="submit" class="btn-auth">Daftar</button>
            </form>

            <div class="auth-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}" data-open-modal="login">Masuk</a>
            </div>
        </section>
    </div>
</div>
@endif
