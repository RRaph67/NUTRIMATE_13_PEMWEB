<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_page_redirects_to_the_food_catalogue(): void
    {
        $this->get('/')->assertRedirect('/foods');
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/foods')->assertRedirect('/login');
    }
}
