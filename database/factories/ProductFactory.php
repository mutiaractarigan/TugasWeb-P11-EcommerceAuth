<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucwords(fake()->words(3, true));

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 99999),
            'description' => fake()->paragraph(),
            // Harga dibulatkan ke ribuan supaya terlihat wajar
            'price' => fake()->numberBetween(10, 2000) * 1000,
            'stock' => fake()->numberBetween(0, 150),
            'is_active' => fake()->boolean(90),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}
