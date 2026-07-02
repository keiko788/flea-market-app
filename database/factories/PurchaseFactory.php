<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
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
            'item_id' => Item::factory(),
            'payment_method' => fake()->numberBetween(1, 2),
            'shipping_postal_code' => fake()->numerify('###-####'),
            'shipping_address' => fake()->address(),
            'shipping_building' => fake()->optional()->secondaryAddress(),
        ];
    }
}
