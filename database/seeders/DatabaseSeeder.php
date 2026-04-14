<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample products
        Product::create([
            'name' => 'Laptop',
            'description' => 'High-performance laptop for work and gaming',
            'price' => 999.99,
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Mouse',
            'description' => 'Wireless ergonomic mouse',
            'price' => 29.99,
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Keyboard',
            'description' => 'Mechanical keyboard with RGB lighting',
            'price' => 89.99,
            'stock' => 75,
        ]);

        Product::create([
            'name' => 'Monitor',
            'description' => '27-inch 4K display',
            'price' => 399.99,
            'stock' => 30,
        ]);

        Product::create([
            'name' => 'Headphones',
            'description' => 'Noise-cancelling wireless headphones',
            'price' => 199.99,
            'stock' => 60,
        ]);

        Product::create([
            'name' => 'USB Cable',
            'description' => 'Fast charging USB-C cable',
            'price' => 9.99,
            'stock' => 200,
        ]);

        Product::create([
            'name' => 'Webcam',
            'description' => 'HD webcam for video calls',
            'price' => 79.99,
            'stock' => 45,
        ]);

        Product::create([
            'name' => 'Tablet',
            'description' => '10-inch tablet with stylus support',
            'price' => 349.99,
            'stock' => 25,
        ]);

        Product::create([
            'name' => 'Smartphone',
            'description' => 'Latest model smartphone',
            'price' => 699.99,
            'stock' => 40,
        ]);

        Product::create([
            'name' => 'Smart Watch',
            'description' => 'Fitness tracking smartwatch',
            'price' => 249.99,
            'stock' => 55,
        ]);

        // Create additional random products using factory
        Product::factory()->count(10)->create();
    }
}
