<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'customer_id', 'provider', 'midtrans_transaction_id', 'order_id_ref', 'transaction_status', 'payment_type',
        'fraud_status', 'gross_amount', 'currency', 'transaction_time', 'settlement_time', 'expiry_time',
        'va_numbers', 'permata_va_number', 'bill_key', 'biller_code', 'masked_card', 'bank', 'store', 'signature_key',
        'actions', 'raw_response', 'refund_amount', 'refunded_at',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
        'va_numbers' => 'array',
        'actions' => 'array',
        'raw_response' => 'array',
        'gross_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }
}
