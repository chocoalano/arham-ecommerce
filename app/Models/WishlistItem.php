<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = ['wishlist_id', 'purchasable_type', 'purchasable_id', 'notes', 'price_at_addition'];

    protected $casts = [
        'price_at_addition' => 'decimal:2',
    ];

    protected $appends = ['image', 'url', 'name', 'price'];

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get optimized image URL with ratio 99:119 (198×238px) for wishlist grid
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

            $image = $product->images()
                ->where('is_thumbnail', true)
                ->first();

            if (! $image) {
                $image = $product->images()
                    ->orderBy('sort_order')
                    ->first();
            }

            // Return ratio 99:119 for grid display
            if ($image) {
                $path = $image->path_ratio_99_119 ?? $image->path;

                return $path ? asset('storage/'.$path) : null;
            }
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

            if ($image) {
                $path = $image->path_ratio_99_119 ?? $image->path;

                return $path ? asset('storage/'.$path) : null;
            }
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

    /**
     * Get product name
     */
    public function getNameAttribute(): ?string
    {
        $purchasable = $this->purchasable;

        if (! $purchasable) {
            return null;
        }

        // If purchasable is ProductVariant, get name from parent Product
        if ($purchasable instanceof ProductVariant) {
            $product = $purchasable->product;

            return $product ? $product->name.($purchasable->name ? ' - '.$purchasable->name : '') : null;
        }

        // If purchasable is Product
        if ($purchasable instanceof Product) {
            return $purchasable->name;
        }

        return null;
    }

    /**
     * Get current price
     */
    public function getPriceAttribute(): ?float
    {
        $purchasable = $this->purchasable;

        if (! $purchasable) {
            return null;
        }

        // Get sale_price or regular price
        $price = $purchasable->sale_price ?? $purchasable->price ?? null;

        // If ProductVariant, might need to check parent Product price
        if ($purchasable instanceof ProductVariant && ! $price) {
            $product = $purchasable->product;
            $price = $product ? ($product->sale_price ?? $product->price) : null;
        }

        return $price ? (float) $price : null;
    }
}
