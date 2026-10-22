<?php

namespace Database\Factories;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'category' => fake()->randomElement(Food::CATEGORIES),
            'price' => fake()->numberBetween(5_000, 75_000),
            'portion_description' => fake()->randomElement(['1 piring', '1 mangkuk', '1 gelas', '100 gram']),
            'description' => fake()->optional()->sentence(12),
            'calories' => fake()->randomFloat(2, 50, 650),
            'protein' => fake()->randomFloat(2, 0, 35),
            'carbs' => fake()->randomFloat(2, 0, 80),
            'fat' => fake()->randomFloat(2, 0, 30),
            'image_url' => null,
        ];
    }

    /**
     * Attach a sample image URL.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes): array => [
            'image_url' => 'https://images.example.com/'.fake()->slug().'.jpg',
        ]);
    }
}
