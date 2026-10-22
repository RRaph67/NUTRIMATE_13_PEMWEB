<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodFormPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_create_form_renders_for_an_authenticated_user(): void
    {
        $user = User::factory()->withProfile()->create();

        $this->actingAs($user)
            ->get('/foods/create')
            ->assertOk()
            ->assertSee('Tambah makanan')
            ->assertSee('name')
            ->assertSee('portion_description')
            ->assertSee(route('foods.store'));
    }

    public function test_storing_a_food_persists_it_and_redirects_to_detail(): void
    {
        $user = User::factory()->withProfile()->create();

        $payload = Food::factory()->raw(['name' => 'Nasi Goreng Spesial']);

        $this->actingAs($user)
            ->post('/foods', $payload)
            ->assertRedirect()
            ->assertSessionHas('status', 'Makanan berhasil ditambahkan.');

        $this->assertDatabaseHas('foods', ['name' => 'Nasi Goreng Spesial']);
    }

    public function test_the_detail_page_renders_for_an_existing_food(): void
    {
        $user = User::factory()->withProfile()->create();

        $food = Food::factory()->create([
            'name' => 'Soto Ayam',
            'category' => 'Protein',
            'portion_description' => '1 mangkuk (300 gram)',
        ]);

        $this->actingAs($user)
            ->get('/foods/'.$food->id)
            ->assertOk()
            ->assertSee('Soto Ayam')
            ->assertSee('Protein')
            ->assertSee('1 mangkuk (300 gram)')
            ->assertSee('Nilai gizi per porsi')
            ->assertSee(route('foods.edit', $food));
    }

    public function test_the_edit_form_prefills_the_food_values(): void
    {
        $user = User::factory()->withProfile()->create();

        $food = Food::factory()->create([
            'name' => 'Es Teh Manis',
            'category' => 'Minuman',
        ]);

        $this->actingAs($user)
            ->get('/foods/'.$food->id.'/edit')
            ->assertOk()
            ->assertSee('Edit makanan')
            ->assertSee('Es Teh Manis')
            ->assertSee(route('foods.update', $food));
    }

    public function test_updating_a_food_persists_changes_and_redirects_to_detail(): void
    {
        $user = User::factory()->withProfile()->create();

        $food = Food::factory()->create(['name' => 'Es Teh Manis']);

        $payload = Food::factory()->raw(['name' => 'Es Teh Jeruk']);

        $this->actingAs($user)
            ->put('/foods/'.$food->id, $payload)
            ->assertRedirect(route('foods.show', $food))
            ->assertSessionHas('status', 'Makanan berhasil diperbarui.');

        $this->assertDatabaseHas('foods', ['id' => $food->id, 'name' => 'Es Teh Jeruk']);
    }

    public function test_deleting_a_food_redirects_to_the_catalogue(): void
    {
        $user = User::factory()->withProfile()->create();

        $food = Food::factory()->create();

        $this->actingAs($user)
            ->delete('/foods/'.$food->id)
            ->assertRedirect(route('foods.index'))
            ->assertSessionHas('status', 'Makanan berhasil dihapus.');

        $this->assertDatabaseMissing('foods', ['id' => $food->id]);
    }

    public function test_guests_cannot_access_the_food_form_pages(): void
    {
        $food = Food::factory()->create();

        $this->get('/foods/create')->assertRedirect(route('login'));
        $this->get('/foods/'.$food->id)->assertRedirect(route('login'));
        $this->get('/foods/'.$food->id.'/edit')->assertRedirect(route('login'));
    }
}
