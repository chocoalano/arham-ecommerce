<?php

namespace Database\Factories;

use App\Models\ShippingQuote;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingQuoteFactory extends Factory
{
    protected $model = ShippingQuote::class;

    public function definition(): array
    {
        return [
            'courier' => $this->faker->randomElement(['jne', 'tiki', 'pos', 'sicepat']),
            'service' => $this->faker->randomElement(['REG', 'YES', 'OKE', 'BEST']),
            'cost' => $this->faker->randomFloat(2, 8000, 45000),
            'etd' => $this->faker->randomElement(['1-2 HARI', '2-3 HARI', '3-5 HARI']),
            'rajaongkir_response' => null,
        ];
    }
}
