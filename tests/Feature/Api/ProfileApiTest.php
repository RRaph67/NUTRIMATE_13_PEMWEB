<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_requires_authentication(): void
    {
        $this->getJson('/api/v1/profile')->assertUnauthorized();
    }

    public function test_profile_returns_the_authenticated_user_profile(): void
    {
        $user = User::factory()->withProfile('Rina Kartika')->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.full_name', 'Rina Kartika')
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonStructure([
                'data' => ['id', 'full_name', 'email', 'registered_at'],
            ]);
    }

    public function test_profile_falls_back_to_user_name_without_a_profile_row(): void
    {
        $user = User::factory()->create(['name' => 'Nama Cadangan']);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.full_name', 'Nama Cadangan');
    }

    public function test_authenticated_user_can_update_profile_name_via_api(): void
    {
        $user = User::factory()->withProfile('Nama Lama')->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->putJson('/api/v1/profile', [
                'name' => 'Nama Baru API',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Nama profil berhasil diperbarui.')
            ->assertJsonPath('data.full_name', 'Nama Baru API');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru API',
        ]);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'full_name' => 'Nama Baru API',
        ]);
    }

    public function test_authenticated_user_can_update_password_via_api(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->putJson('/api/v1/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Password berhasil diperbarui.');

        $this->assertTrue(Hash::check('newpassword456', $user->fresh()->password));
    }

    public function test_update_password_fails_with_invalid_current_password_via_api(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->putJson('/api/v1/profile/password', [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);
    }
}
