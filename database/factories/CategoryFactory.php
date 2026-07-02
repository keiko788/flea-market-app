<?php

namespace Database\Factories;

use App\Models\Categorym;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorym>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
