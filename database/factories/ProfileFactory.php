<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
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
            'profile_image_path' => 'items/sample.jpg',
            'postal_code' => fake()->numerify('###-####'),
            'address' => fake()->address(),
            'building' => fake()->optional()->secondaryAddress(),
        ];
    }
}
