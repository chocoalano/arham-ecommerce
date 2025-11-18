<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 'address_id', 'courier', 'service', 'cost', 'etd', 'rajaongkir_response',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'rajaongkir_response' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
