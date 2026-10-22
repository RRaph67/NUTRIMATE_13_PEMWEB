<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get(route('profile.show'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_view_profile_page(): void
    {
        $user = User::factory()->withProfile('Budi Santoso')->create();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee($user->email);
    }

    public function test_user_can_update_profile_name(): void
    {
        $user = User::factory()->withProfile('Nama Lama')->create();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru Yang Valid',
            ])
            ->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Nama profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru Yang Valid',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'full_name' => 'Nama Baru Yang Valid',
        ]);
    }

    public function test_user_cannot_update_name_with_invalid_data(): void
    {
        $user = User::factory()->withProfile('Nama Asli')->create();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'ab', // too short
            ])
            ->assertSessionHasErrors(['name']);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $this->actingAs($user)
            ->put(route('profile.password.update'), [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Password berhasil diperbarui.');

        $this->assertTrue(Hash::check('newpassword456', $user->fresh()->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $this->actingAs($user)
            ->put(route('profile.password.update'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertSessionHasErrors(['current_password']);
    }
}
