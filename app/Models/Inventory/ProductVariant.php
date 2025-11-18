<?php

namespace App\Models\Inventory;

use App\Models\RawMaterial\RawMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'inventory';

    protected $fillable = [
        'product_id',
        'raw_material_id',
        'sku_variant',
        'barcode',
        'color',
        'size',
        'cost_price',
        'price',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL'];

    protected static function booted(): void
    {
        static::creating(function (self $variant) {
            if (blank($variant->sku_variant)) {
                $variant->sku_variant = self::generateUniqueSkuVariant(
                    productSku: $variant->product?->sku,
                    color: $variant->color,
                    size: $variant->size
                );
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseVariantStock::class, 'product_variant_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'product_variant_id');
    }

    /** Stock total semua gudang */
    public function getTotalStockAttribute(): int
    {
        return (int) $this->stocks()->sum('qty');
    }

    /** Stock on hand (qty - reserved) semua gudang */
    public function getStockOnHandAttribute(): int
    {
        return (int) $this->stocks()->sum(DB::raw('qty - reserved_qty'));
    }

    /** Scope varian aktif */
    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    // di ProductVariant
    public function stockInWarehouse(int $warehouseId): int
    {
        return (int) $this->stocks()
            ->where('warehouse_id', $warehouseId)
            ->value('qty') ?? 0;
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'product_variant_id');
    }

    public function scopeForUser(Builder $query, ?User $user = null): Builder
    {
        // Gunakan user terautentikasi jika tidak diberikan
        $user = $user ?? auth()->user();

        // Jika tidak ada user → aman: hasil kosong
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        // Superadmin → tanpa pembatasan
        if (method_exists($user, 'hasRole') && $user->hasRole('Superadmin')) {
            return $query;
        }

        // Non-superadmin → batasi varian yang tersedia di gudang miliknya
        $wid = (int) ($user->warehouse_id ?? 0);
        if ($wid <= 0) {
            return $query->whereRaw('1 = 0');
        }

        // Tampilkan varian yang ada stoknya (on hand > 0) di gudang user
        return $query->whereHas('stocks', function (Builder $q) use ($wid) {
            $q->where('warehouse_id', $wid)
                ->whereRaw('(COALESCE(qty,0) - COALESCE(reserved_qty,0)) > 0');
        });
    }

    /**
     * Generate SKU varian unik.
     *
     * Pola default:
     *   {BASE}-{COLOR}-{SIZE}-{YYMMDD}-{RAND4}
     *   - BASE: dari SKU produk jika ada, jika tidak dari nama produk → slug uppercase (mis. "PRD")
     *   - COLOR & SIZE: di-normalisasi (slug uppercase), otomatis di-skip jika kosong
     *   - RAND4: 4 char acak (A-Z0-9)
     *   - Dijamin unik dengan pengecekan ke DB (kolom sku_variant)
     *
     * @param  string|null $productSku  SKU produk induk (disarankan)
     * @param  string|null $color       Warna varian (opsional)
     * @param  string|null $size        Ukuran varian (opsional)
     * @param  int         $maxLen      Batas panjang SKU varian (sesuaikan kolom DB), default 64
     */
    public static function generateUniqueSkuVariant(?string $productSku = null, ?string $color = null, ?string $size = null, int $maxLen = 64): string
    {
        // 1) Normalisasi komponen
        $base = strtoupper(Str::slug((string) ($productSku ?: 'PRD'), '-'));
        $cPart = $color ? strtoupper(Str::slug($color, '-')) : null;
        $sPart = $size ? strtoupper(Str::slug($size, '-')) : null;

        // 2) Rakit bagian dinamis
        $date = now()->format('ymd');
        $rand4 = strtoupper(Str::random(4));
        $sep = '-';

        // Komponen utama tanpa rand dulu (agar trimming konsisten)
        $parts = array_values(array_filter([$base, $cPart, $sPart]));
        $staticSuffix = "{$sep}{$date}{$sep}{$rand4}";

        // 3) Trim agar total panjang <= $maxLen
        $head = implode($sep, $parts);
        $allowHeadLen = max(1, $maxLen - mb_strlen($staticSuffix));
        $headTrimmed = mb_substr($head, 0, $allowHeadLen);

        // Bersihkan potongan yang berhenti di separator (hindari trailing '-')
        $headTrimmed = rtrim($headTrimmed, $sep);

        $candidate = "{$headTrimmed}{$staticSuffix}";

        // 4) Loop cek unik: jika tabrakan, ganti RAND4
        $tries = 0;
        while (static::query()->where('sku_variant', $candidate)->exists()) {
            $rand4 = strtoupper(Str::random(4));
            $staticSuffix = "{$sep}{$date}{$sep}{$rand4}";
            $allowHeadLen = max(1, $maxLen - mb_strlen($staticSuffix));
            $headTrimmed = rtrim(mb_substr($head, 0, $allowHeadLen), $sep);
            $candidate = "{$headTrimmed}{$staticSuffix}";

            if (++$tries > 25) {
                // last resort: acak total
                $candidate = 'VAR-' . strtoupper(Str::random(10));
                if (!static::query()->where('sku_variant', $candidate)->exists()) {
                    break;
                }
            }
        }

        return $candidate;
    }
}
