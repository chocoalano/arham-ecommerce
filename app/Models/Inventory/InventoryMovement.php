<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class InventoryMovement extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'inventory';
    protected $fillable = [
        'transaction_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'product_variant_id',
        'qty_change',
        'type',
        'occurred_at',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'qty_change' => 'int',
        'occurred_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function from_warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function to_warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->hasRole('Superadmin')) {
            return $query;
        }

        $wid = (int) ($user->warehouse_id ?? 0);
        if ($wid <= 0) {
            return $query->whereRaw('1 = 0'); // aman
        }

        return $query->where(function ($q) use ($wid) {
            $q->whereHas(
                'transaction',
                fn($trx) =>
                $trx->where('source_warehouse_id', $wid)
                    ->orWhere('destination_warehouse_id', $wid)
            );
        });
    }
}
