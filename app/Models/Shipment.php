<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'courier', 'service', 'waybill', 'cost', 'etd', 'shipped_at', 'delivered_at', 'receiver_name', 'status', 'raw_response', 'origin_id', 'destination_id',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'raw_response' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
