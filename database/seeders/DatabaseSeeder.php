<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrator',
            'email' => 'admin@zippin.com',
        ]);

        User::factory()->customer()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);

        // Product::factory(1_000)->create();
    }
}
