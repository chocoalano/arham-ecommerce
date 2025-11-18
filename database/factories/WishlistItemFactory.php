<?php

namespace Database\Factories;

use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistItemFactory extends Factory
{
    protected $model = WishlistItem::class;

    public function definition(): array
    {
        return [
            'notes' => $this->faker->optional()->sentence(6),
            'price_at_addition' => $this->faker->optional()->randomFloat(2, 10000, 500000),
        ];
    }
}
