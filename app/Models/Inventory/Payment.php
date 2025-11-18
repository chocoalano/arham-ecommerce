<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'inventory';
    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'reference_no',
        'paid_at',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // App\Models\Payment.php
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
                'invoice.transaction',
                fn($trx) =>
                $trx->where('source_warehouse_id', $wid)
                    ->orWhere('destination_warehouse_id', $wid)
            )->orWhereHas(
                    'receiver',
                    fn($receiver) =>
                    $receiver->where('warehouse_id', $wid)
                );
        });
    }

}
