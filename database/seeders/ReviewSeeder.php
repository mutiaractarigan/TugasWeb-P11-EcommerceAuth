<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Review hanya dari pelanggan yang benar-benar membeli produk tersebut
        $purchases = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->select('orders.user_id', 'order_items.product_id')
            ->distinct()
            ->get();

        foreach ($purchases as $purchase) {
            Review::factory()->create([
                'user_id' => $purchase->user_id,
                'product_id' => $purchase->product_id,
            ]);
        }
    }
}
