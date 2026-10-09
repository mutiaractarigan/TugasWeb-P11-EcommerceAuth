<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $customers = User::where('role', 'user')->get();

        foreach ($customers as $customer) {
            // Setiap pelanggan punya 1–2 alamat, alamat pertama jadi default
            $addresses = Address::factory(fake()->numberBetween(1, 2))
                ->for($customer)
                ->sequence(['is_default' => true], ['is_default' => false])
                ->create(['recipient_name' => $customer->name]);

            // 1–3 order per pelanggan
            for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
                $createdAt = fake()->dateTimeBetween('-3 months');

                $order = Order::factory()->for($customer)->create([
                    'address_id' => $addresses->random()->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 1–4 produk berbeda per order, harga diambil dari harga produk
                $total = 0;
                foreach ($products->random(fake()->numberBetween(1, 4)) as $product) {
                    $qty = fake()->numberBetween(1, 3);
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                    ]);
                    $total += $qty * $product->price;
                }
                $order->update(['total_amount' => $total]);

                // Status pembayaran mengikuti status order
                $paymentStatus = match ($order->status) {
                    'pending' => 'pending',
                    'cancelled' => 'failed',
                    default => 'success',
                };

                Payment::factory()->for($order)->create([
                    'amount' => $total,
                    'status' => $paymentStatus,
                    'paid_at' => $paymentStatus === 'success' ? $createdAt : null,
                ]);
            }
        }
    }
}
