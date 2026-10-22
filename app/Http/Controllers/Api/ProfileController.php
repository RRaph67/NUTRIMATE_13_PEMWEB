<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Show the profile of the authenticated user.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('profile');

        return response()->json([
            'data' => new ProfileResource($user),
        ]);
    }

    /**
     * Update the authenticated user's name.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
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

        $user->load('profile');

        return response()->json([
            'message' => 'Nama profil berhasil diperbarui.',
            'data' => new ProfileResource($user),
        ]);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}
