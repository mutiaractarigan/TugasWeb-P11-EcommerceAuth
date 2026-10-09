<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun untuk testing tiap role (password: password)
        User::factory()->admin()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.test',
        ]);

        User::factory()->editor()->create([
            'name' => 'Editor Konten',
            'email' => 'editor@toko.test',
        ]);

        User::factory()->create([
            'name' => 'Pelanggan Biasa',
            'email' => 'user@toko.test',
        ]);

        // Pelanggan lain
        User::factory(25)->create();
    }
}
