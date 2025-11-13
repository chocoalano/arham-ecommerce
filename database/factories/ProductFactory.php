<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(3, true));

        return [
            'sku' => strtoupper(Str::random(10)),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(5),
            'brand_id' => null,
            'short_description' => $this->faker->optional()->sentence(12),
            'description' => $this->faker->optional()->paragraph(4),
            'weight_gram' => $this->faker->numberBetween(100, 1200),
            'length_mm' => $this->faker->optional()->numberBetween(60, 400),
            'width_mm' => $this->faker->optional()->numberBetween(60, 400),
            'height_mm' => $this->faker->optional()->numberBetween(60, 400),
            'price' => $this->faker->randomFloat(2, 10000, 500000),
            'sale_price' => $this->faker->optional(0.35)->randomFloat(2, 8000, 400000),
            'is_featured' => $this->faker->boolean(10),
            'status' => 'active',
            'attributes' => ['flavor' => $this->faker->randomElement(['matcha', 'red velvet', 'taro', 'chocolate', 'vanilla'])],
            'currency' => 'IDR',
            'meta_title' => $this->faker->optional()->sentence(3),
            'meta_description' => $this->faker->optional()->sentence(8),
            'stock' => $this->faker->numberBetween(0, 300),
        ];
    }
}
