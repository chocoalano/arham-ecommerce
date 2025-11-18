<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement(['S', 'M', 'L', 'XL']).' / '.$this->faker->randomElement(['Original', 'Matcha', 'Chocolate', 'Taro']);

        return [
            'sku' => strtoupper(Str::random(12)),
            'name' => $name,
            'options' => ['size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']), 'style' => $this->faker->randomElement(['Original', 'Matcha', 'Chocolate', 'Taro'])],
            'weight_gram' => $this->faker->numberBetween(100, 800),
            'price' => $this->faker->randomFloat(2, 9000, 300000),
            'sale_price' => $this->faker->optional(0.25)->randomFloat(2, 8000, 250000),
            'is_active' => true,
        ];
    }
}
