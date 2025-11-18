<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseVariantStock extends Model
{
    use HasFactory;
    protected $table = 'warehouse_variant_stocks';
    protected $connection = 'inventory';

    protected $fillable = [
        'warehouse_id','product_variant_id','qty','reserved_qty',
    ];

    protected $casts = [
        'qty'          => 'int',
        'reserved_qty' => 'int',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
