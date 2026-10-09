<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    private static array $titles = [
        'Tips Memilih Laptop untuk Kuliah',
        'Promo Gajian: Diskon Hingga 50%',
        'Cara Merawat Sepatu Lari agar Awet',
        'Rekomendasi Skincare untuk Pemula',
        '5 Peralatan Dapur yang Wajib Dimiliki',
        'Panduan Ukuran Baju di Toko Kami',
        'Gratis Ongkir ke Seluruh Indonesia',
        'Cara Melacak Status Pesanan',
        'Tren Fashion Pria Tahun Ini',
        'Olahraga di Rumah dengan Alat Sederhana',
        'Metode Pembayaran yang Tersedia',
        'Kebijakan Pengembalian Barang',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(self::$titles),
            'body' => implode("\n\n", fake()->paragraphs(3)),
        ];
    }
}
