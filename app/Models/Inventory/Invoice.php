<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'inventory';
    protected $fillable = [
        'transaction_id',
        'invoice_number',
        'issued_at',
        'due_at',
        'subtotal',
        'discount_total',
        'tax_total',
        'shipping_fee',
        'total_amount',
        'paid_amount',
        'is_paid',
    ];

    protected $casts = [
        'issued_at'       => 'datetime',
        'due_at'          => 'datetime',
        'subtotal'        => 'decimal:2',
        'discount_total'  => 'decimal:2',
        'tax_total'       => 'decimal:2',
        'shipping_fee'    => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'paid_amount'     => 'decimal:2',
        'is_paid'         => 'bool',
    ];

    /* ====================== BOOT: AUTO INVOICE NUMBER ====================== */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            // set issued_at default
            if (empty($invoice->issued_at)) {
                $invoice->issued_at = now();
            }

            // generate invoice_number bila kosong
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::nextInvoiceNumber($invoice->issued_at);
            }
        });
    }

    /**
     * Generate nomor invoice harian unik: INV-YYYYMMDD-####.
     * Menggunakan transaksi + lock untuk meminimalkan race condition.
     */
    public static function nextInvoiceNumber($issuedAt = null): string
    {
        $date = Carbon::parse($issuedAt ?? now());

        return DB::transaction(function () use ($date) {
            $prefix = 'INV-' . $date->format('Ymd') . '-';

            // Ambil nomor terakhir di hari yang sama (dengan lock)
            $last = static::whereDate('issued_at', $date->toDateString())
                ->where('invoice_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->max('invoice_number');

            $next = 1;
            if ($last) {
                $lastSeq = (int) substr($last, -4);
                $next = $lastSeq + 1;
            }

            // Bangun kandidat & pastikan unik (retry kecil jika bentrok)
            do {
                $candidate = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
                $exists = static::where('invoice_number', $candidate)->exists();
                $next++;
            } while ($exists);

            return $candidate;
        });
    }

    /* =========================== RELATIONS =========================== */

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /* ============================ SCOPES ============================ */

    /** Helper: sisa tagihan */
    public function getOutstandingAttribute(): string
    {
        $outstanding = (float) $this->total_amount - (float) $this->paid_amount;
        return number_format(max(0, $outstanding), 2, '.', '');
    }

    /** Scope: belum lunas */
    public function scopeUnpaid($q)
    {
        return $q->where('is_paid', false);
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
            $q->whereHas('transaction', fn($trx) =>
                $trx->where('source_warehouse_id', $wid)
                    ->orWhere('destination_warehouse_id', $wid)
            )->orWhereHas('payments.receiver', fn($receiver) =>
                $receiver->where('warehouse_id', $wid)
            );
        });
    }
}
