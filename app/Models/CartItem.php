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
        'cart_id', 'purchasable_type', 'purchasable_id', 'sku', 'name', 'weight_gram',
        'quantity', 'price', 'subtotal', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected $appends = ['image', 'url'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get optimized image URL with ratio 51:52 (255×260px) for cart thumbnails
     */
    public function getImageAttribute(): ?string
    {
        $purchasable = $this->purchasable;

        if (! $purchasable) {
            return null;
        }

        // If purchasable is ProductVariant, get from parent Product
        if ($purchasable instanceof ProductVariant) {
            $product = $purchasable->product;

            if (! $product) {
                return null;
            }

            // Try to get thumbnail first, then first image with ratio
            $image = $product->images()
                ->where('is_thumbnail', true)
                ->first();

            if (! $image) {
                $image = $product->images()
                    ->orderBy('sort_order')
                    ->first();
            }

            // Return ratio 51:52 (square-ish, good for small thumbnails)
            return $image?->path_ratio_51_52 ?? $image?->path;
        }

        // If purchasable is Product
        if ($purchasable instanceof Product) {
            $image = $purchasable->images()
                ->where('is_thumbnail', true)
                ->first();

            if (! $image) {
                $image = $purchasable->images()
                    ->orderBy('sort_order')
                    ->first();
            }

            // Return ratio 51:52 (square-ish, good for small thumbnails)
            return $image?->path_ratio_51_52 ?? $image?->path;
        }

        return null;
    }

    /**
     * Get product URL
     */
    public function getUrlAttribute(): ?string
    {
        $purchasable = $this->purchasable;

        if (! $purchasable) {
            return null;
        }

        // If purchasable is ProductVariant, get URL from parent Product
        if ($purchasable instanceof ProductVariant) {
            $product = $purchasable->product;

            return $product ? route('catalog.show', ['slug' => $product->slug]) : null;
        }

        // If purchasable is Product
        if ($purchasable instanceof Product) {
            return route('catalog.show', ['slug' => $purchasable->slug]);
        }

        return null;
    }
}
