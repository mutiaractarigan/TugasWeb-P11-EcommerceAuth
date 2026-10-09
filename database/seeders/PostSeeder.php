<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@toko.test')->first();
        $editor = User::where('email', 'editor@toko.test')->first();

        Post::factory(3)->for($admin)->create();
        Post::factory(5)->for($editor)->create();
    }
}
