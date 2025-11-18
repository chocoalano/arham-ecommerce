<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'label', 'recipient_name', 'phone', 'address_line1', 'address_line2', 'postal_code',
        'rajaongkir_province_id', 'province_name', 'rajaongkir_city_id', 'city_name', 'rajaongkir_subdistrict_id', 'subdistrict_name',
        'latitude', 'longitude', 'is_default_shipping', 'is_default_billing',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_default_shipping' => 'boolean',
        'is_default_billing' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'address_id');
    }

    public function billingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    public function shippingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }
}
