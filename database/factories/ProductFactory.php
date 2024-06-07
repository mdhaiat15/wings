<?php

namespace Database\Factories;

use App\Models\Product;
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
    protected $model = Product::class;
    
    public function definition(): array
    {
        return [
            'product_code' => strtoupper($this->faker->unique()->bothify('??????????')),
            'product_name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10000, 50000),
            'currency' => 'IDR', 
            'discount' => $this->faker->optional()->randomFloat(2, 0, 100), 
            'dimension' => $this->faker->optional()->randomElement(['10x20x30', '15x25x35', '20x30x40']),
            'unit' => $this->faker->randomElement(['pcs']),
        ];
    }
}
