<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => 'SKU-' . strtoupper($this->faker->unique()->bothify('??###')),
            'name' => $this->faker->word(),
            'unit_cost' => 50.00,
            'stock_quantity' => 100,
            'low_stock_threshold' => 10,
        ];
    }
}