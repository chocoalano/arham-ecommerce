<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(1200, 1200, 'food', true),
            'alt_text' => $this->faker->optional()->sentence(3),
            'is_thumbnail' => false,
            'sort_order' => 0,
        ];
    }
}
