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
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        // Sync dengan Inventory Product jika ada perubahan
        $this->syncWithInventory();
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
