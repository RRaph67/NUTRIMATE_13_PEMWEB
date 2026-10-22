<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_profile_and_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Pendaftaran berhasil.')
            ->assertJsonPath('data.user.email', 'budi@example.com')
            ->assertJsonPath('data.user.profile.full_name', 'Budi Santoso')
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'profile' => ['full_name'], 'created_at'],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'budi@example.com']);
        $this->assertDatabaseHas('profiles', ['full_name' => 'Budi Santoso']);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->assertIsString($response->json('data.token'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_register_validates_duplicate_email_with_indonesian_message(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email'])
            ->assertJsonPath(
                'errors.email.0',
                'Email sudah terdaftar. Gunakan email lain atau masuk.',
            );
    }

    public function test_register_validates_password_confirmation(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'full_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'beda12345',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_returns_a_bearer_token(): void
    {
        User::factory()->withProfile('Siti Aminah')->create([
            'email' => 'siti@example.com',
            'password' => 'rahasia123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'siti@example.com',
            'password' => 'rahasia123',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Login berhasil.')
            ->assertJsonPath('data.user.email', 'siti@example.com')
            ->assertJsonPath('data.user.profile.full_name', 'Siti Aminah');

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'siti@example.com',
            'password' => 'rahasia123',
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'siti@example.com',
            'password' => 'password-salah',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'Email atau password salah.');
    }

    public function test_me_requires_a_valid_token(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();

        $this->withToken('token-tidak-valid')
            ->getJson('/api/v1/auth/me')
            ->assertUnauthorized();
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        $user = User::factory()->withProfile('Andi Wijaya')->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonPath('data.profile.full_name', 'Andi Wijaya');
    }

    public function test_logout_revokes_the_current_token_only(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $otherToken = $user->createToken('other-device')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logout berhasil. Token sudah tidak berlaku.');

        $this->assertDatabaseCount('personal_access_tokens', 1);

        // Each real HTTP request boots a fresh guard; reset it to mimic that.
        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertUnauthorized();

        $this->withToken($otherToken)
            ->getJson('/api/v1/auth/me')
            ->assertOk();
    }
}
