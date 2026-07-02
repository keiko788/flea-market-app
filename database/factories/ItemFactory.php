<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'description' => fake()->realText(80),
            'image_path' => 'items/sample.jpg',
            'condition' => fake()->numberBetween(1, 4),
            'price' => fake()->numberBetween(300, 10000),
            'brand_name' => fake()->company(),
        ];
    }
}
