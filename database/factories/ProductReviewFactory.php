<?php

namespace Database\Factories;

use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(3, 5),
            'title' => $this->faker->optional()->sentence(4),
            'content' => $this->faker->paragraph(),
            'status' => 'approved',
            'parent_id' => null,
        ];
    }
}
