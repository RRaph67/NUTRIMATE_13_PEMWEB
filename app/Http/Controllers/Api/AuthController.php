<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Register a new user and return the first API token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->profile()->create([
                'full_name' => $validated['full_name'],
            ]);

            return $user;
        });

        return $this->tokenResponse($user, 'Pendaftaran berhasil.', $request, 201);
    }

    /**
     * Authenticate the user and return a new API token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = User::with('profile')
            ->where('email', $request->validated('email'))
            ->firstOrFail();

        return $this->tokenResponse($user, 'Login berhasil.', $request);
    }

    /**
     * Revoke the token used for the current request.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil. Token sudah tidak berlaku.',
        ]);
    }

    /**
     * Return the authenticated user with their profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('profile');

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'profile' => new ProfileResource($user),
            ],
        ]);
    }

    /**
     * Build the standard token response payload.
     */
    private function tokenResponse(User $user, string $message, Request $request, int $status = 200): JsonResponse
    {
        $user->loadMissing('profile');

        $deviceName = trim((string) $request->input('device_name')) ?: 'api-client';

        return response()->json([
            'message' => $message,
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken($deviceName)->plainTextToken,
                'token_type' => 'Bearer',
            ],
        ], $status);
    }
}
