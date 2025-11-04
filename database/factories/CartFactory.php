<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'session_id' => null,
            'currency' => 'IDR',
            'expires_at' => now()->addDays(7),
            'meta' => null,
        ];
    }
}
