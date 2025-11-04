<?php

namespace Database\Factories;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'name' => 'Default Wishlist',
        ];
    }
}
