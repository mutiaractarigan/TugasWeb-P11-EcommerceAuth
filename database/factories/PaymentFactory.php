<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method' => fake()->randomElement(['transfer_bank', 'e_wallet', 'cod']),
            'amount' => 0,
            'status' => 'pending',
            'paid_at' => null,
        ];
    }
}
