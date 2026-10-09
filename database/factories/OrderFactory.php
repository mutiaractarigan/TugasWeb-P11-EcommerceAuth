<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'order_number' => 'INV-' . fake()->unique()->numerify('########'),
            'status' => fake()->randomElement(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
            'total_amount' => 0,
        ];
    }
}
