<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 500),
            'currency_id' => Currency::factory(),
            'tax_cost' => fake()->randomFloat(2, 1, 50),
            'manufacturing_cost' => fake()->randomFloat(2, 1, 100),
        ];
    }
}
