<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => str($name)->title(),
            'slug' => str($name)->slug(),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(1, 10_000),
            'stock' => fake()->numberBetween(0, 1_00),
        ];
    }
}
