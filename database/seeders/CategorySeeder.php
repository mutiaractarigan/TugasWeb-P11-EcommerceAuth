<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik' => 'Gadget, aksesoris komputer, dan perangkat elektronik.',
            'Fashion Pria' => 'Pakaian, sepatu, dan aksesoris untuk pria.',
            'Fashion Wanita' => 'Pakaian, tas, dan aksesoris untuk wanita.',
            'Rumah Tangga' => 'Peralatan dapur dan kebutuhan rumah.',
            'Kesehatan & Kecantikan' => 'Skincare, perawatan tubuh, dan suplemen.',
            'Olahraga' => 'Perlengkapan olahraga dan aktivitas luar ruangan.',
        ];

        foreach ($categories as $name => $description) {
            Category::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}
