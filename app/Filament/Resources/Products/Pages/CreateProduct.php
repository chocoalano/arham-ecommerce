<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

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
                    // Restore the product
                    $product->restore();

                    // Restore variants
                    $product->variants()->restore();

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
                    // Force delete variants first
                    $product->variants()->forceDelete();

                    // Force delete images
                    $product->images()->forceDelete();

                    // Force delete the product
                    $product->forceDelete();

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

    protected function handleRecordCreation(array $data): Model
    {
        // Check if product with same SKU exists (including soft deleted)
        $existingProduct = static::getModel()::withTrashed()
            ->where('sku', $data['sku'])
            ->first();

        // If product exists and is soft deleted
        if ($existingProduct && $existingProduct->trashed()) {
            Notification::make()
                ->warning()
                ->title('Product Already Exists (Deleted)')
                ->body("A product with SKU **{$data['sku']}** already exists but has been deleted. Use the action buttons above to restore or permanently delete it.")
                ->persistent()
                ->send();

            // Store the existing product ID in session for header actions
            session()->put('pending_restore_product_id', $existingProduct->id);

            // Halt the creation process
            $this->halt();
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

        // Clear session if exists
        session()->forget('pending_restore_product_id');

        // Create the product
        $product = static::getModel()::create($data);

        return $product;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
