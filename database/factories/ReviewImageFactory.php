<?php

namespace Database\Factories;

use App\Models\ReviewImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewImageFactory extends Factory
{
    protected $model = ReviewImage::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(800, 800, 'product', true),
            'sort_order' => 0,
        ];
    }
}
