<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile of the currently authenticated user.
     */
    public function show(Request $request): View
    {
        $user = $request->user()->load('profile');

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated user's name.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        DB::transaction(function () use ($user, $validated): void {
            $user->update([
                'name' => $validated['name'],
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['full_name' => $validated['name']],
            );
        });

        return redirect()
            ->route('profile.show')
            ->with('status', 'Nama profil berhasil diperbarui.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('profile.show')
            ->with('status', 'Password berhasil diperbarui.');
    }
}
