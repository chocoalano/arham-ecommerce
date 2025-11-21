<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        $pendingProductId = session('pending_restore_product_id');

        if (! $pendingProductId) {
            return [];
        }

        $product = Product::withTrashed()->find($pendingProductId);

        if (! $product || ! $product->trashed()) {
            session()->forget('pending_restore_product_id');

            return [];
        }

        return [
            Action::make('restore')
                ->label('Pulihkan Produk yang Dihapus')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Pulihkan Produk?')
                ->modalDescription("Pulihkan produk dengan SKU: {$product->sku}")
                ->modalSubmitActionLabel('Ya, Pulihkan')
                ->action(function () use ($product) {
                    DB::transaction(function () use ($product) {
                        // Restore the product
                        // Variants will be restored automatically via Product model boot method
                        $product->restore();
                    });

                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->success()
                        ->title('Produk Dipulihkan')
                        ->body('Produk dan variannya berhasil dipulihkan.')
                        ->send();

                    // Redirect to edit page
                    return redirect()->to(ProductResource::getUrl('edit', ['record' => $product->id]));
                }),
            Action::make('force_delete')
                ->label('Hapus Permanen')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('⚠️ PERINGATAN: Hapus Permanen Produk')
                ->modalDescription(function () use ($product) {
                    $variantsCount = $product->variants()->withTrashed()->count();
                    $imagesCount = $product->images()->count();
                    $reviewsCount = $product->reviews()->count();
                    $wishlistCount = $product->wishlistItems()->count();
                    $cartCount = $product->cartItems()->count();
                    $orderItemsCount = $product->orderItems()->count();

                    return "**TINDAKAN INI AKAN MENGHAPUS PERMANEN:**\n\n".
                        "📦 Produk: **{$product->sku}** - {$product->name}\n\n".
                        "**Data yang akan ikut terhapus:**\n".
                        "• {$variantsCount} Varian Produk\n".
                        "• {$imagesCount} Gambar Produk\n".
                        "• {$reviewsCount} Review/Rating\n".
                        "• {$wishlistCount} Item Wishlist\n".
                        "• {$cartCount} Item Keranjang\n".
                        "• {$orderItemsCount} Item di Order\n".
                        "• Semua relasi kategori\n\n".
                        "⚠️ **PERINGATAN:** Tindakan ini **TIDAK DAPAT DIBATALKAN**. Semua data akan hilang selamanya!\n\n".
                        "Ketik 'HAPUS' untuk melanjutkan.";
                })
                ->modalSubmitActionLabel('Ya, Hapus Permanen')
                ->modalIcon('heroicon-o-exclamation-triangle')
                ->modalIconColor('danger')
                ->action(function () use ($product) {
                    DB::transaction(function () use ($product) {
                        // Force delete the product
                        // Cascade deletes handled by Product model boot method
                        $product->forceDelete();
                    });

                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->success()
                        ->title('Produk Dihapus Permanen')
                        ->body('Produk telah dihapus secara permanen. Anda sekarang dapat membuat produk baru.')
                        ->send();

                    // Stay on create page
                    return redirect()->to(ProductResource::getUrl('create'));
                }),
            Action::make('cancel')
                ->label('Batal')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->action(function () {
                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->info()
                        ->title('Dibatalkan')
                        ->body('Anda dapat membuat produk dengan SKU yang berbeda.')
                        ->send();
                }),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove catalog_product_id from data as it's not a database column
        unset($data['catalog_product_id']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Check for duplicate SKU (including soft deleted)
            $existingProduct = static::getModel()::withTrashed()
                ->where('sku', $data['sku'])
                ->first();

            // If product exists and is soft deleted
            if ($existingProduct && $existingProduct->trashed()) {
                // Store the existing product ID in session for header actions
                session()->flash('pending_restore_product_id', $existingProduct->id);
                session()->flash('pending_restore_sku', $data['sku']);

                Notification::make()
                    ->warning()
                    ->title('Produk Sudah Ada (Dihapus)')
                    ->body("Produk dengan SKU **{$data['sku']}** sudah ada tetapi telah dihapus. Gunakan tombol aksi di atas untuk memulihkan atau menghapusnya secara permanen.")
                    ->persistent()
                    ->send();

                // Redirect to same page to show header actions
                $this->redirect(static::getResource()::getUrl('create'), navigate: false);
            }

            // If product exists and is active
            if ($existingProduct) {
                Notification::make()
                    ->danger()
                    ->title('Produk Sudah Ada')
                    ->body("Produk dengan SKU **{$data['sku']}** sudah ada dan aktif.")
                    ->send();

                $this->halt();
            }

            // Check for duplicate slug (including soft deleted)
            $existingSlug = static::getModel()::withTrashed()
                ->where('slug', $data['slug'])
                ->where('sku', '!=', $data['sku']) // Different product
                ->first();

            if ($existingSlug) {
                Notification::make()
                    ->danger()
                    ->title('Slug Sudah Ada')
                    ->body("Produk dengan slug **{$data['slug']}** sudah ada. Harap gunakan slug yang berbeda.")
                    ->send();

                $this->halt();
            }

            // Check for duplicate variant SKUs in current form data
            if (isset($data['variants']) && is_array($data['variants'])) {
                $variantSkus = array_filter(array_column($data['variants'], 'sku'));

                // Check if all variants have SKU
                if (count($variantSkus) !== count($data['variants'])) {
                    Notification::make()
                        ->danger()
                        ->title('SKU Varian Kosong')
                        ->body('Semua varian harus memiliki SKU yang valid.')
                        ->send();

                    $this->halt();
                }

                $duplicateVariantSkus = array_diff_assoc($variantSkus, array_unique($variantSkus));

                if (! empty($duplicateVariantSkus)) {
                    Notification::make()
                        ->danger()
                        ->title('SKU Varian Duplikat')
                        ->body('Beberapa SKU varian terduplikasi dalam form: '.implode(', ', array_unique($duplicateVariantSkus)))
                        ->send();

                    $this->halt();
                }

                // Check if variant SKUs exist in OTHER products (not this product)
                // This allows updating existing variants for the same product
                $existingVariantsInOtherProducts = ProductVariant::withTrashed()
                    ->whereIn('sku', $variantSkus)
                    ->where('product_id', '!=', $data['id'] ?? 0) // Exclude current product if editing
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

            // Clear session if exists
            session()->forget('pending_restore_product_id');
            session()->forget('pending_restore_sku');

            // Extract relationships data
            $categories = $data['category_id'] ?? [];
            unset($data['category_id']);

            // Extract variants and images (will be handled by Filament's relationship)
            // but we need to ensure they're in correct format
            $variants = $data['variants'] ?? [];
            $images = $data['images'] ?? [];
            unset($data['variants'], $data['images']);

            // Create the product
            $product = static::getModel()::create($data);

            // Attach categories (many-to-many relationship)
            if (! empty($categories)) {
                $product->categories()->sync($categories);
            }

            // Create or update variants using updateOrCreate based on SKU
            if (! empty($variants)) {
                $processedSkus = [];

                foreach ($variants as $variantData) {
                    if (isset($variantData['sku'])) {
                        // Use updateOrCreate to handle existing variants by SKU
                        $product->variants()->updateOrCreate(
                            [
                                'sku' => $variantData['sku'],
                            ],
                            array_merge($variantData, ['product_id' => $product->id])
                        );

                        $processedSkus[] = $variantData['sku'];
                    }
                }

                // Delete variants that are not in the current list (cleanup)
                if (! empty($processedSkus)) {
                    $product->variants()
                        ->whereNotIn('sku', $processedSkus)
                        ->delete();
                }
            }

            // Create images manually to ensure proper ordering
            if (! empty($images)) {
                foreach ($images as $index => $imageData) {
                    // Ensure sort_order is set
                    if (! isset($imageData['sort_order'])) {
                        $imageData['sort_order'] = $index;
                    }
                    $product->images()->create($imageData);
                }
            }

            return $product;
        });
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Produk Berhasil Dibuat')
            ->body('Produk telah berhasil dibuat.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
