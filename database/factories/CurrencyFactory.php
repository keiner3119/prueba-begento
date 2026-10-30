<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'name' => fake()->word(),
            'symbol' => '$',
            'exchange_rate' => fake()->randomFloat(4, 0.1, 5000),
        ];
    }
}
