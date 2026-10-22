<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Makan Sehat,');
        $response->assertSee('Kantong Aman.');
        $response->assertSee('Food Database');
        $response->assertSee('Budget Recommendation');
        $response->assertSee('Meal Planner');
        $response->assertSee('Nutrition Calculator');
    }

    public function test_home_page_shows_login_modal_triggers_for_guests(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('data-open-modal="login"', false);
        $response->assertSee('data-open-modal="register"', false);
        $response->assertSee('id="authModal"', false);
    }

    public function test_home_page_shows_user_profile_link_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('profile.show'));
        $response->assertDontSee('id="authModal"', false);
    }
}
