<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    // 60 produk realistis: [nama, harga]
    private array $catalog = [
        'Elektronik' => [
            ['Mouse Wireless Logitech M185', 145000],
            ['Keyboard Mechanical Rexus Legionaire MX5', 389000],
            ['Headset Gaming Fantech HG11', 215000],
            ['Flashdisk SanDisk Ultra 64GB', 89000],
            ['Power Bank Xiaomi 10000mAh', 199000],
            ['Kabel Data USB Type-C 1 Meter', 35000],
            ['TWS Earbuds Baseus WM01', 249000],
            ['Webcam Full HD 1080p', 275000],
            ['SSD Samsung 870 EVO 500GB', 899000],
            ['Smartwatch Xiaomi Redmi Watch 3', 1199000],
        ],
        'Fashion Pria' => [
            ['Kemeja Flanel Kotak Lengan Panjang', 129000],
            ['Kaos Polos Cotton Combed 30s', 49000],
            ['Celana Chino Slim Fit', 159000],
            ['Jaket Bomber Pria', 225000],
            ['Sepatu Sneakers Kanvas Putih', 189000],
            ['Hoodie Basic Fleece', 149000],
            ['Ikat Pinggang Kulit Asli', 99000],
            ['Celana Jeans Regular Fit', 199000],
            ['Topi Baseball Polos', 45000],
            ['Sandal Gunung Outdoor', 135000],
        ],
        'Fashion Wanita' => [
            ['Blouse Wanita Lengan Balon', 89000],
            ['Rok Plisket Panjang', 99000],
            ['Hijab Segi Empat Voal', 55000],
            ['Tas Selempang Wanita Mini', 139000],
            ['Gamis Polos Busui Friendly', 219000],
            ['Cardigan Rajut Oversize', 125000],
            ['Celana Kulot Highwaist', 109000],
            ['Flat Shoes Wanita', 115000],
            ['Dress Midi Motif Bunga', 179000],
            ['Dompet Wanita Panjang', 79000],
        ],
        'Rumah Tangga' => [
            ['Panci Set Stainless 5 in 1', 299000],
            ['Rice Cooker Miyako 1.8 Liter', 329000],
            ['Teflon Wajan Anti Lengket 24cm', 119000],
            ['Dispenser Air Galon Bawah', 1150000],
            ['Rak Piring Stainless 2 Susun', 149000],
            ['Blender Philips HR2115', 689000],
            ['Set Pisau Dapur 6 Pcs', 95000],
            ['Tempat Sampah Injak 12 Liter', 85000],
            ['Sprei Katun Jepang 160x200', 189000],
            ['Lampu LED Philips 12 Watt', 42000],
        ],
        'Kesehatan & Kecantikan' => [
            ['Facial Wash Wardah Acnederm 60ml', 32000],
            ['Sunscreen Azarine SPF 45', 65000],
            ['Serum Vitamin C Somethinc 20ml', 115000],
            ['Moisturizer Skintific 5X Ceramide', 139000],
            ['Lip Tint Implora', 25000],
            ['Shampoo Pantene Anti Ketombe 340ml', 48000],
            ['Masker Wajah Sheet Mask (isi 10)', 59000],
            ['Vitamin C 1000mg (isi 30)', 75000],
            ['Hand Sanitizer 500ml', 39000],
            ['Termometer Digital', 55000],
        ],
        'Olahraga' => [
            ['Matras Yoga TPE 6mm', 149000],
            ['Dumbbell Vinyl 2 x 2kg', 120000],
            ['Sepatu Lari Ortuseight', 459000],
            ['Raket Badminton Yonex Muscle Power', 389000],
            ['Bola Sepak Mikasa Size 5', 275000],
            ['Botol Minum Olahraga 1 Liter', 45000],
            ['Resistance Band Set 5 Level', 89000],
            ['Tali Skipping Speed Rope', 39000],
            ['Jersey Olahraga Dry Fit', 85000],
            ['Shuttlecock Kok Bulu (isi 12)', 95000],
        ],
    ];

    public function run(): void
    {
        foreach ($this->catalog as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->firstOrFail();

            foreach ($products as [$name, $price]) {
                Product::factory()->create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => "{$name} original dan berkualitas. Cocok untuk kebutuhan sehari-hari, "
                        . 'dikirim dari Medan dengan packing aman.',
                    'price' => $price,
                ]);
            }
        }
    }
}
