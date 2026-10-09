<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'description', 'price', 'stock', 'is_active'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ---------- Relationships ----------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ---------- Scopes ----------

    // Product::active() → hanya produk yang dijual
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    // Product::inStock() → stok masih ada
    public function scopeInStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    // Product::priceBetween(100000, 500000)
    public function scopePriceBetween(Builder $query, int $min, int $max): void
    {
        $query->whereBetween('price', [$min, $max]);
    }
}
