<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
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
                ->label('Restore Deleted Product')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Restore Product?')
                ->modalDescription("Restore product with SKU: {$product->sku}")
                ->modalSubmitActionLabel('Yes, Restore')
                ->action(function () use ($product) {
                    DB::transaction(function () use ($product) {
                        // Restore the product
                        $product->restore();

                        // Restore variants
                        $product->variants()->restore();

                        // Restore images (if soft deleted)
                        // Note: ProductImage doesn't use SoftDeletes, but keeping for future-proofing
                        if (method_exists($product->images()->getModel(), 'restore')) {
                            $product->images()->restore();
                        }
                    });

                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->success()
                        ->title('Product Restored')
                        ->body('The product and its variants have been successfully restored.')
                        ->send();

                    // Redirect to edit page
                    return redirect()->to(ProductResource::getUrl('edit', ['record' => $product->id]));
                }),
            Action::make('force_delete')
                ->label('Delete Permanently')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Permanently Delete Product?')
                ->modalDescription("This will permanently delete product with SKU: {$product->sku}. This action cannot be undone.")
                ->modalSubmitActionLabel('Yes, Delete Permanently')
                ->action(function () use ($product) {
                    DB::transaction(function () use ($product) {
                        // Force delete all morphMany relations first
                        $product->reviews()->forceDelete();
                        $product->wishlistItems()->forceDelete();
                        $product->cartItems()->forceDelete();
                        $product->orderItems()->forceDelete();

                        // Force delete variants and their relations
                        $variants = $product->variants()->withTrashed()->get();
                        foreach ($variants as $variant) {
                            $variant->reviews()->forceDelete();
                            $variant->wishlistItems()->forceDelete();
                            $variant->cartItems()->forceDelete();
                            $variant->orderItems()->forceDelete();
                        }
                        $product->variants()->forceDelete();

                        // Force delete images and their files
                        $images = $product->images()->get();
                        foreach ($images as $image) {
                            $this->deleteImageFiles($image);
                        }
                        $product->images()->forceDelete();

                        // Detach categories (many-to-many)
                        $product->categories()->detach();

                        // Force delete the product
                        $product->forceDelete();
                    });

                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->success()
                        ->title('Product Permanently Deleted')
                        ->body('The product has been permanently deleted. You can now create a new product.')
                        ->send();

                    // Stay on create page
                    return redirect()->to(ProductResource::getUrl('create'));
                }),
            Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->action(function () {
                    session()->forget('pending_restore_product_id');

                    Notification::make()
                        ->info()
                        ->title('Cancelled')
                        ->body('You can create a product with a different SKU.')
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
                    ->title('Product Already Exists (Deleted)')
                    ->body("A product with SKU **{$data['sku']}** already exists but has been deleted. Use the action buttons above to restore or permanently delete it.")
                    ->persistent()
                    ->send();

                // Redirect to same page to show header actions
                $this->redirect(static::getResource()::getUrl('create'), navigate: false);
            }

            // If product exists and is active
            if ($existingProduct) {
                Notification::make()
                    ->danger()
                    ->title('Product Already Exists')
                    ->body("A product with SKU **{$data['sku']}** already exists and is active.")
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
                    ->title('Slug Already Exists')
                    ->body("A product with slug **{$data['slug']}** already exists. Please use a different slug.")
                    ->send();

                $this->halt();
            }

            // Check for duplicate variant SKUs before creating
            if (isset($data['variants']) && is_array($data['variants'])) {
                $variantSkus = array_column($data['variants'], 'sku');
                $duplicateVariantSkus = array_diff_assoc($variantSkus, array_unique($variantSkus));

                if (! empty($duplicateVariantSkus)) {
                    Notification::make()
                        ->danger()
                        ->title('Duplicate Variant SKUs')
                        ->body('Some variant SKUs are duplicated: '.implode(', ', array_unique($duplicateVariantSkus)))
                        ->send();

                    $this->halt();
                }

                // Check if variant SKUs exist in database (including soft deleted)
                $existingVariantSkus = ProductVariant::withTrashed()
                    ->whereIn('sku', $variantSkus)
                    ->pluck('sku')
                    ->toArray();

                if (! empty($existingVariantSkus)) {
                    Notification::make()
                        ->danger()
                        ->title('Variant SKU Already Exists')
                        ->body('These variant SKUs already exist: '.implode(', ', $existingVariantSkus))
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

            // Create variants manually to ensure no duplicates
            if (! empty($variants)) {
                foreach ($variants as $variantData) {
                    $product->variants()->create($variantData);
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
            ->title('Product Created')
            ->body('The product has been successfully created.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Delete image files from storage
     */
    protected function deleteImageFiles(ProductImage $image): void
    {
        $paths = [
            $image->path,
            $image->path_ratio_27_28,
            $image->path_ratio_108_53,
            $image->path_ratio_51_52,
            $image->path_ratio_99_119,
        ];

        foreach ($paths as $path) {
            if ($path && \Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
        }
    }
}
