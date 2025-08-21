<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Infrastructure\Persistence\Product\ProductModel;

class ProductFactory extends Factory
{
    protected $model = ProductModel::class;
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2,0,10000000),
        ];
    }
}
