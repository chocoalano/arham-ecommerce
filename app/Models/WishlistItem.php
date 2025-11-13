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

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }
}
