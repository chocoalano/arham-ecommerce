<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->randomElement(['Home', 'Office']),
            'recipient_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->optional()->secondaryAddress(),
            'postal_code' => $this->faker->postcode(),
            'rajaongkir_province_id' => $this->faker->numberBetween(1, 34),
            'province_name' => $this->faker->state(),
            'rajaongkir_city_id' => $this->faker->numberBetween(1, 500),
            'city_name' => $this->faker->city(),
            'rajaongkir_subdistrict_id' => $this->faker->numberBetween(1, 2000),
            'subdistrict_name' => $this->faker->citySuffix(),
            'latitude' => $this->faker->latitude(-11, 6),
            'longitude' => $this->faker->longitude(95, 141),
            'is_default_shipping' => false,
            'is_default_billing' => false,
        ];
    }
}
