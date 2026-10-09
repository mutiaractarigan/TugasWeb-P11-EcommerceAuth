<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_realistic_data(): void
    {
        $this->seed();

        $this->assertGreaterThanOrEqual(50, Product::count());
        $this->assertSame(6, Category::count());
        $this->assertSame('admin', User::where('email', 'admin@toko.test')->value('role'));
        $this->assertSame('editor', User::where('email', 'editor@toko.test')->value('role'));
        $this->assertSame('user', User::where('email', 'user@toko.test')->value('role'));

        // Total order harus sama dengan jumlah (qty × harga) item-itemnya
        $order = Order::with('items')->first();
        $this->assertSame(
            (int) $order->total_amount,
            $order->items->sum(fn ($item) => $item->quantity * $item->price)
        );
    }
}
