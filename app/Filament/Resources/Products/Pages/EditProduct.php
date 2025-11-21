<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->modalHeading('Hapus Produk ke Trash')
                ->modalDescription(function () {
                    $variantsCount = $this->record->variants()->count();

                    return "Anda akan menghapus produk **{$this->record->sku}** - {$this->record->name} dan **{$variantsCount} varian** ke trash.\n\n".
                        "✅ Data masih bisa dipulihkan kembali\n".
                        "✅ File gambar tetap aman di storage\n".
                        "✅ Relasi data tetap terjaga\n\n".
                        '💡 **Tip:** Data yang dihapus masih bisa dipulihkan dari menu trash.';
                })
                ->successNotificationTitle('Produk Dipindahkan ke Trash'),
            ForceDeleteAction::make()
                ->modalHeading('⚠️ PERINGATAN: Hapus Permanen Produk')
                ->modalDescription(function () {
                    $variantsCount = $this->record->variants()->withTrashed()->count();
                    $imagesCount = $this->record->images()->count();
                    $reviewsCount = $this->record->reviews()->count();
                    $wishlistCount = $this->record->wishlistItems()->count();
                    $cartCount = $this->record->cartItems()->count();
                    $orderItemsCount = $this->record->orderItems()->count();

                    return "**TINDAKAN INI AKAN MENGHAPUS PERMANEN:**\n\n".
                        "📦 Produk: **{$this->record->sku}** - {$this->record->name}\n\n".
                        "**Data yang akan ikut terhapus:**\n".
                        "• {$variantsCount} Varian Produk\n".
                        "• {$imagesCount} Gambar Produk\n".
                        "• {$reviewsCount} Review/Rating\n".
                        "• {$wishlistCount} Item Wishlist\n".
                        "• {$cartCount} Item Keranjang\n".
                        "• {$orderItemsCount} Item di Order\n".
                        "• Semua relasi kategori\n\n".
                        "⚠️ **PERINGATAN:** Tindakan ini **TIDAK DAPAT DIBATALKAN**. Semua data akan hilang selamanya!\n\n".
                        'File gambar fisik juga akan **DIHAPUS** dari storage.';
                })
                ->modalIcon('heroicon-o-exclamation-triangle')
                ->modalIconColor('danger')
                ->modalSubmitActionLabel('Ya, Hapus Permanen Selamanya')
                ->successNotificationTitle('Produk Dihapus Permanen'),
            RestoreAction::make()
                ->modalHeading('Pulihkan Produk dari Trash')
                ->modalDescription(function () {
                    $variantsCount = $this->record->variants()->withTrashed()->count();

                    return "Anda akan memulihkan produk **{$this->record->sku}** - {$this->record->name} dan **{$variantsCount} varian** dari trash.\n\n".
                        "✅ Produk akan kembali aktif\n".
                        "✅ Semua varian akan dipulihkan\n".
                        "✅ Relasi data akan kembali normal\n\n".
                        '💡 Produk akan muncul kembali di daftar produk aktif.';
                })
                ->successNotificationTitle('Produk Berhasil Dipulihkan'),
        ];
    }

    protected function beforeSave(): void
    {
        // Validate and handle variants with updateOrCreate based on SKU
        $this->handleVariantsBeforeSave();

        // Sync dengan Inventory Product jika ada perubahan
        $this->syncWithInventory();
    }

    protected function handleVariantsBeforeSave(): void
    {
        $data = $this->form->getState();

        // Check for duplicate variant SKUs in current form data
        if (isset($data['variants']) && is_array($data['variants'])) {
            $variantSkus = array_filter(array_column($data['variants'], 'sku'));

            // Check for duplicates within the form
            $duplicateVariantSkus = array_diff_assoc($variantSkus, array_unique($variantSkus));

            if (! empty($duplicateVariantSkus)) {
                Notification::make()
                    ->danger()
                    ->title('SKU Varian Duplikat')
                    ->body('Beberapa SKU varian terduplikasi dalam form: '.implode(', ', array_unique($duplicateVariantSkus)))
                    ->send();

                $this->halt();
            }

            // Check if variant SKUs exist in OTHER products
            $existingVariantsInOtherProducts = \App\Models\ProductVariant::withTrashed()
                ->whereIn('sku', $variantSkus)
                ->where('product_id', '!=', $this->record->id)
                ->pluck('sku')
                ->toArray();

            if (! empty($existingVariantsInOtherProducts)) {
                Notification::make()
                    ->danger()
                    ->title('SKU Varian Sudah Digunakan Produk Lain')
                    ->body('SKU varian ini sudah digunakan produk lain: '.implode(', ', $existingVariantsInOtherProducts))
                    ->send();

                $this->halt();
            }
        }
    }

    protected function afterSave(): void
    {
        // Sync variants setelah product disimpan
        $this->syncVariantsWithInventory();

        Notification::make()
            ->success()
            ->title('Produk berhasil diperbarui')
            ->body('Data produk telah tersinkronisasi dengan sistem inventory.')
            ->send();
    }

    protected function syncWithInventory(): void
    {
        $data = $this->form->getState();

        // Cek apakah produk memiliki relasi dengan inventory
        if ($this->record->product_inventory) {
            $inventoryProduct = $this->record->product_inventory;

            // Update data inventory product
            $inventoryProduct->update([
                'name' => $data['name'] ?? $inventoryProduct->name,
                'description' => $data['description'] ?? $inventoryProduct->description,
                'brand' => isset($data['brand_id'])
                    ? \App\Models\Brand::query()->find($data['brand_id'])?->name
                    : $inventoryProduct->brand,
                // SKU tidak diubah karena merupakan foreign key
            ]);
        }
    }

    protected function syncVariantsWithInventory(): void
    {
        // Sync variants jika ada perubahan
        if ($this->record->product_inventory) {
            $productVariants = $this->record->variants()->get();

            foreach ($productVariants as $variant) {
                // Cari variant di inventory berdasarkan SKU
                $inventoryVariant = \App\Models\Inventory\ProductVariant::where('sku_variant', $variant->sku)
                    ->where('product_id', $this->record->product_inventory->id)
                    ->first();

                if ($inventoryVariant) {
                    // Update data variant di inventory
                    $inventoryVariant->update([
                        'color' => $variant->color ?? $inventoryVariant->color,
                        'size' => $variant->size ?? $inventoryVariant->size,
                        'price' => $variant->price ?? $inventoryVariant->price,
                        'status' => $variant->is_active ? 'active' : 'inactive',
                    ]);

                    // Sync stock dengan warehouse
                    $this->syncWarehouseStock($inventoryVariant, $variant);
                }
            }
        }
    }

    protected function syncWarehouseStock($inventoryVariant, $variant): void
    {
        // Update stock di warehouse jika ada perubahan
        $warehouseStock = \App\Models\Inventory\WarehouseVariantStock::where('product_variant_id', $inventoryVariant->id)
            ->first();

        if ($warehouseStock) {
            // Hitung perubahan stock
            $currentTotalStock = $this->record->stock ?? 0;
            $oldTotalStock = $warehouseStock->quantity ?? 0;

            if ($currentTotalStock != $oldTotalStock) {
                $stockDifference = $currentTotalStock - $oldTotalStock;

                // Update stock di warehouse
                $warehouseStock->qty = $currentTotalStock;
                $warehouseStock->save();

                // Catat pergerakan inventory
                // Check if user exists in inventory database
                $userId = auth()->id();
                $userExists = \Illuminate\Support\Facades\DB::connection('inventory')
                    ->table('users')
                    ->where('id', $userId)
                    ->exists();

                \App\Models\Inventory\InventoryMovement::create([
                    'product_variant_id' => $inventoryVariant->id,
                    'from_warehouse_id' => $warehouseStock->warehouse_id,
                    'to_warehouse_id' => null,
                    'qty_change' => $stockDifference,
                    'type' => $stockDifference > 0 ? 'in' : 'out',
                    'occurred_at' => now(),
                    'remarks' => 'Sinkronisasi stock dari admin e-commerce',
                    'created_by' => $userExists ? $userId : null,
                ]);
            }
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load relasi inventory untuk ditampilkan di form
        if ($this->record->product_inventory) {
            $data['catalog_product_id'] = $this->record->product_inventory->sku;
        }

        return $data;
    }
}
