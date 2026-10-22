<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_page_returns_successful_response(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_guests_are_redirected_to_the_home_page_when_accessing_foods(): void
    {
        $this->get('/foods')->assertRedirect(route('home'));
    }

    public function test_guests_are_redirected_to_the_home_page_when_accessing_login_or_register(): void
    {
        $this->get('/login')->assertRedirect(route('home'));
        $this->get('/register')->assertRedirect(route('home'));
    }
}
