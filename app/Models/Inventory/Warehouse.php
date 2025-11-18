<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'inventory';

    protected $fillable = [
        'code',
        'name',
        'address',
        'district',
        'city',
        'province',
        'postal_code',
        'lat',
        'lng',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'is_active' => 'bool',
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'warehouse_id');
    }
    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseVariantStock::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function sourceTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'source_warehouse_id');
    }

    public function destinationTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'destination_warehouse_id');
    }

    public function scopeForUser(Builder $query, ?User $user = null): Builder
    {
        // Pakai user terautentikasi jika argumen null
        $user = $user ?? auth()->user();

        // Jika tidak ada user, aman: kembalikan kosong
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        // Superadmin: tanpa pembatasan
        if (method_exists($user, 'hasRole') && $user->hasRole('Superadmin')) {
            return $query;
        }

        // Non-superadmin: batasi ke warehouse miliknya
        $wid = (int) ($user->warehouse_id ?? 0);

        return $wid > 0
            ? $query->whereKey($wid)          // where('id', $wid)
            : $query->whereRaw('1 = 0');      // aman jika user belum punya warehouse
    }
}
