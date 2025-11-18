<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku', 'name', 'slug', 'brand_id', 'short_description', 'description',
        'weight_gram', 'length_mm', 'width_mm', 'height_mm',
        'price', 'sale_price', 'is_featured', 'status', 'attributes',
        'currency', 'meta_title', 'meta_description', 'stock',
    ];

    protected $casts = [
        'attributes' => 'array',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /* ---------------- Relations ---------------- */

    public function product_inventory(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'sku', 'sku');
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product', 'product_id', 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(ProductReview::class, 'reviewable');
    }

    public function wishlistItems(): MorphMany
    {
        return $this->morphMany(WishlistItem::class, 'purchasable');
    }

    public function cartItems(): MorphMany
    {
        return $this->morphMany(CartItem::class, 'purchasable');
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'purchasable');
    }

    /* ---------------- Helpers ---------------- */

    protected function effectivePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) ($this->sale_price ?? $this->price ?? 0)
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where('stock', '>', 0);
    }

    /** Atomically adjust stock (positive or negative). */
    public function adjustStock(int $delta): void
    {
        if ($delta === 0) {
            return;
        }
        $delta > 0 ? $this->increment('stock', $delta) : $this->decrement('stock', abs($delta));
        $this->refresh();
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating');
    }
}
