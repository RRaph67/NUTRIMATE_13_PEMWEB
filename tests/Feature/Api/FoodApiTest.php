<?php

namespace Tests\Feature\Api;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FoodApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->withProfile()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nasi Goreng',
            'category' => 'Karbohidrat',
            'price' => 15000,
            'portion_description' => '1 piring (250 g)',
            'description' => 'Nasi goreng dengan telur dan sayuran.',
            'calories' => 420,
            'protein' => 12.5,
            'carbs' => 60,
            'fat' => 14,
            'image_url' => 'https://images.example.com/nasi-goreng.jpg',
        ], $overrides);
    }

    public function test_food_endpoints_require_authentication(): void
    {
        $this->getJson('/api/v1/foods')->assertUnauthorized();
        $this->postJson('/api/v1/foods', $this->payload())->assertUnauthorized();

        $food = Food::factory()->create();

        $this->getJson('/api/v1/foods/'.$food->id)->assertUnauthorized();
        $this->putJson('/api/v1/foods/'.$food->id, $this->payload())->assertUnauthorized();
        $this->deleteJson('/api/v1/foods/'.$food->id)->assertUnauthorized();
    }

    public function test_index_returns_paginated_foods(): void
    {
        Food::factory()->count(3)->create();

        $this->withToken($this->token)
            ->getJson('/api/v1/foods')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'name',
                    'category',
                    'price',
                    'portion_description',
                    'description',
                    'nutrition' => ['calories', 'protein', 'carbs', 'fat'],
                    'image_url',
                    'created_at',
                    'updated_at',
                ]],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                'filters' => ['q', 'category', 'sort'],
            ]);
    }

    public function test_index_searches_by_name_case_insensitively(): void
    {
        Food::factory()->create(['name' => 'Ayam Bakar Madu']);
        Food::factory()->create(['name' => 'Tempe Mendoan']);

        $this->withToken($this->token)
            ->getJson('/api/v1/foods?q=ayam')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Ayam Bakar Madu');
    }

    public function test_index_filters_by_category(): void
    {
        Food::factory()->create(['name' => 'Bayam Rebus', 'category' => 'Sayuran']);
        Food::factory()->create(['name' => 'Jus Jeruk', 'category' => 'Minuman']);

        $this->withToken($this->token)
            ->getJson('/api/v1/foods?category=Sayuran')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Bayam Rebus');
    }

    public function test_index_sorts_by_lowest_price(): void
    {
        Food::factory()->create(['name' => 'Mahal', 'price' => 50_000]);
        Food::factory()->create(['name' => 'Murah', 'price' => 5_000]);
        Food::factory()->create(['name' => 'Sedang', 'price' => 20_000]);

        $response = $this->withToken($this->token)
            ->getJson('/api/v1/foods?sort=termurah')
            ->assertOk();

        $this->assertSame(
            ['Murah', 'Sedang', 'Mahal'],
            array_column($response->json('data'), 'name'),
        );
    }

    public function test_index_sorts_by_highest_calories(): void
    {
        Food::factory()->create(['name' => 'Rendah', 'calories' => 100]);
        Food::factory()->create(['name' => 'Tinggi', 'calories' => 600]);

        $response = $this->withToken($this->token)
            ->getJson('/api/v1/foods?sort=kalori')
            ->assertOk();

        $this->assertSame(
            ['Tinggi', 'Rendah'],
            array_column($response->json('data'), 'name'),
        );
    }

    public function test_index_rejects_a_sort_value_outside_the_whitelist(): void
    {
        $this->withToken($this->token)
            ->getJson('/api/v1/foods?sort=price%3B%20drop%20table%20foods')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sort']);
    }

    public function test_store_creates_a_food_with_a_uuid(): void
    {
        $response = $this->withToken($this->token)
            ->postJson('/api/v1/foods', $this->payload())
            ->assertCreated()
            ->assertJsonPath('message', 'Makanan berhasil ditambahkan.')
            ->assertJsonPath('data.name', 'Nasi Goreng')
            ->assertJsonPath('data.category', 'Karbohidrat');

        $this->assertSame(420.0, (float) $response->json('data.nutrition.calories'));

        $this->assertTrue(Str::isUuid($response->json('data.id')));
        $this->assertSame(15000.0, (float) $response->json('data.price'));

        $this->assertDatabaseHas('foods', [
            'name' => 'Nasi Goreng',
            'category' => 'Karbohidrat',
        ]);
    }

    public function test_store_validates_required_fields_in_indonesian(): void
    {
        $this->withToken($this->token)
            ->postJson('/api/v1/foods', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'category', 'price', 'portion_description', 'calories'])
            ->assertJsonPath('errors.name.0', 'Nama makanan wajib diisi.')
            ->assertJsonPath('errors.category.0', 'Kategori wajib dipilih.');
    }

    public function test_store_rejects_an_unknown_category(): void
    {
        $this->withToken($this->token)
            ->postJson('/api/v1/foods', $this->payload(['category' => 'Seafood']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['category']);
    }

    public function test_store_rejects_negative_nutrition_values(): void
    {
        $this->withToken($this->token)
            ->postJson('/api/v1/foods', $this->payload(['calories' => -1, 'protein' => -2]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['calories', 'protein']);
    }

    public function test_show_returns_a_single_food(): void
    {
        $food = Food::factory()->create(['name' => 'Sate Ayam']);

        $this->withToken($this->token)
            ->getJson('/api/v1/foods/'.$food->id)
            ->assertOk()
            ->assertJsonPath('data.id', $food->id)
            ->assertJsonPath('data.name', 'Sate Ayam');
    }

    public function test_show_returns_404_for_an_unknown_uuid(): void
    {
        $this->withToken($this->token)
            ->getJson('/api/v1/foods/'.Str::uuid())
            ->assertNotFound();
    }

    public function test_show_returns_404_for_a_malformed_id(): void
    {
        $this->withToken($this->token)
            ->getJson('/api/v1/foods/bukan-uuid')
            ->assertNotFound();
    }

    public function test_update_changes_the_food(): void
    {
        $food = Food::factory()->create(['name' => 'Nasi Putih', 'price' => 5000]);

        $response = $this->withToken($this->token)
            ->putJson('/api/v1/foods/'.$food->id, $this->payload([
                'name' => 'Nasi Merah',
                'price' => 8000,
                'calories' => 300,
            ]))
            ->assertOk()
            ->assertJsonPath('message', 'Makanan berhasil diperbarui.')
            ->assertJsonPath('data.name', 'Nasi Merah');

        $this->assertSame(300.0, (float) $response->json('data.nutrition.calories'));

        $this->assertDatabaseHas('foods', [
            'id' => $food->id,
            'name' => 'Nasi Merah',
        ]);
    }

    public function test_update_accepts_patch(): void
    {
        $food = Food::factory()->create(['name' => 'Nasi Putih']);

        $this->withToken($this->token)
            ->patchJson('/api/v1/foods/'.$food->id, $this->payload(['name' => 'Nasi Kuning']))
            ->assertOk()
            ->assertJsonPath('data.name', 'Nasi Kuning');
    }

    public function test_update_validates_the_payload(): void
    {
        $food = Food::factory()->create();

        $this->withToken($this->token)
            ->putJson('/api/v1/foods/'.$food->id, $this->payload(['name' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_destroy_deletes_the_food(): void
    {
        $food = Food::factory()->create();

        $this->withToken($this->token)
            ->deleteJson('/api/v1/foods/'.$food->id)
            ->assertOk()
            ->assertJsonPath('message', 'Makanan berhasil dihapus.');

        $this->assertDatabaseMissing('foods', ['id' => $food->id]);
    }

    public function test_destroy_returns_404_for_an_unknown_food(): void
    {
        $this->withToken($this->token)
            ->deleteJson('/api/v1/foods/'.Str::uuid())
            ->assertNotFound();
    }
}
