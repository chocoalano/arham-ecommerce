<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'sku', 'name', 'options', 'weight_gram', 'price', 'sale_price', 'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Reviews for this variant */
    public function reviews(): MorphMany
    {
        return $this->morphMany(ProductReview::class, 'reviewable');
    }

    /** Wishlist items referencing this variant */
    public function wishlistItems(): MorphMany
    {
        return $this->morphMany(WishlistItem::class, 'purchasable');
    }

    /** Cart items referencing this variant */
    public function cartItems(): MorphMany
    {
        return $this->morphMany(CartItem::class, 'purchasable');
    }

    /** Order items referencing this variant */
    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'purchasable');
    }

    public function effectivePrice(): float
    {
        return (float) ($this->sale_price ?? $this->price ?? 0);
    }
}
