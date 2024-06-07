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
            'product_code' => $this->faker->unique()->bothify('??-##########'), // Kombinasi huruf dan angka
            'product_name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10000, 50000), // Harga antara 10.00 sampai 1000.00
            'currency' => 'IDR', // Atau bisa gunakan $this->faker->currencyCode untuk berbagai macam kode mata uang
            'discount' => $this->faker->optional()->randomFloat(2, 0, 100), // Diskon antara 0.00 sampai 100.00 atau null
            'dimension' => $this->faker->optional()->randomElement(['10x20x30', '15x25x35', '20x30x40']),
            'unit' => $this->faker->randomElement(['pcs']),
        ];
    }
}
