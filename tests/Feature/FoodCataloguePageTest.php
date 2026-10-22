<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodCataloguePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_catalogue_page_renders_for_an_authenticated_user(): void
    {
        $user = User::factory()->withProfile()->create();

        Food::factory()->create(['name' => 'Soto Ayam', 'category' => 'Protein']);
        Food::factory()->create(['name' => 'Es Teh Manis', 'category' => 'Minuman']);

        $this->actingAs($user)
            ->get('/foods')
            ->assertOk()
            ->assertSee('Katalog makanan')
            ->assertSee('Soto Ayam')
            ->assertSee('Es Teh Manis');
    }

    public function test_the_search_filter_narrows_the_catalogue(): void
    {
        $user = User::factory()->withProfile()->create();

        Food::factory()->create(['name' => 'Soto Ayam', 'category' => 'Protein']);
        Food::factory()->create(['name' => 'Es Teh Manis', 'category' => 'Minuman']);

        $this->actingAs($user)
            ->get('/foods?q=soto')
            ->assertOk()
            ->assertSee('Soto Ayam')
            ->assertDontSee('Es Teh Manis');
    }
}
