<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id','purchasable_type','purchasable_id','sku','name','weight_gram',
        'quantity','price','subtotal','meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }
}
