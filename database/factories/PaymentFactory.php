<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'settlement', 'capture', 'expire']);

        return [
            'provider' => 'midtrans',
            'midtrans_transaction_id' => $this->faker->uuid(),
            'order_id_ref' => strtoupper(Str::random(5)).$this->faker->numerify('######'),
            'transaction_status' => $status,
            'payment_type' => $this->faker->randomElement(['bank_transfer', 'qris', 'credit_card']),
            'fraud_status' => null,
            'gross_amount' => $this->faker->randomFloat(2, 50000, 2000000),
            'currency' => 'IDR',
            'transaction_time' => now(),
            'settlement_time' => $status === 'settlement' ? now() : null,
            'expiry_time' => now()->addDays(1),
            'va_numbers' => null,
            'permata_va_number' => null,
            'bill_key' => null,
            'biller_code' => null,
            'masked_card' => null,
            'bank' => null,
            'store' => null,
            'signature_key' => Str::random(32),
            'actions' => null,
            'raw_response' => null,
            'refund_amount' => null,
            'refunded_at' => null,
        ];
    }
}
