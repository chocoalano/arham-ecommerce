<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'purchasable_type', 'purchasable_id', 'sku', 'name', 'weight_gram', 'quantity', 'price', 'subtotal', 'meta',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'meta' => 'array',
    ];

    protected $appends = ['image'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get optimized image URL with ratio 51:52 (255×260px) for order item thumbnails
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

            // Return ratio 51:52 for small thumbnails
            if ($image) {
                $path = $image->path_ratio_51_52 ?? $image->path;

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
                $path = $image->path_ratio_51_52 ?? $image->path;

                return $path ? asset('storage/'.$path) : null;
            }
        }

        return null;
    }
}
