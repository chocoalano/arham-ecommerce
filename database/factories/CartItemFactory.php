<?php

namespace Database\Factories;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 5000, 200000);
        $qty = $this->faker->numberBetween(1, 4);

        return [
            'sku' => strtoupper($this->faker->bothify('SKU-####-??')),
            'name' => ucfirst($this->faker->words(3, true)),
            'weight_gram' => $this->faker->numberBetween(50, 800),
            'quantity' => $qty,
            'price' => $price,
            'subtotal' => $price * $qty,
            'meta' => null,
        ];
    }
}
