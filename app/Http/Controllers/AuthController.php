<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     *
     * After registering the user is redirected to the login page instead of
     * being logged in automatically (safe, predictable default).
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->profile()->create([
                'full_name' => $validated['full_name'],
            ]);
        });

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan masuk dengan email dan password Anda.');
    }

    /**
     * Show the login form.
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()
            ->intended(route('profile.show', absolute: false))
            ->with('status', 'Anda berhasil masuk.');
    }

    /**
     * Log the authenticated user out of the application.
     */
    public function destroy(Request $request): RedirectResponse
    {
        auth('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Anda telah keluar.');
    }
}
