<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => '出品者',
            'email' => 'seller@example.com',
        ]);

        User::factory()->create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
        ]);
    }
}
