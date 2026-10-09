<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    private static array $comments = [
        5 => ['Barang sesuai deskripsi, pengiriman cepat. Mantap!', 'Kualitas bagus banget, recommended seller.', 'Sudah langganan di sini, tidak pernah kecewa.'],
        4 => ['Barang bagus, cuma pengirimannya agak lama.', 'Sesuai harga, packing rapi.', 'Oke, sesuai ekspektasi.'],
        3 => ['Lumayan, tapi warnanya sedikit beda dari foto.', 'Standar, sesuai harga.', 'Kualitas biasa saja.'],
        2 => ['Ukurannya tidak pas, agak kecewa.', 'Packing kurang aman, ada sedikit lecet.'],
        1 => ['Barang rusak saat diterima.', 'Tidak sesuai pesanan.'],
    ];

    public function definition(): array
    {
        // Rating dibuat lebih sering bagus, seperti review toko online sungguhan
        $rating = fake()->randomElement([5, 5, 5, 4, 4, 4, 3, 3, 2, 1]);

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => $rating,
            'comment' => fake()->randomElement(self::$comments[$rating]),
        ];
    }
}
