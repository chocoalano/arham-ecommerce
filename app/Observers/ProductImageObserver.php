<?php

namespace App\Observers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageObserver
{
    /**
     * Handle the ProductImage "updating" event.
     */
    public function updating(ProductImage $productImage): void
    {
        // Jika path berubah, hapus file lama
        if ($productImage->isDirty('path') && $productImage->getOriginal('path')) {
            Storage::disk('public')->delete($productImage->getOriginal('path'));
        }
    }

    /**
     * Handle the ProductImage "deleted" event.
     */
    public function deleted(ProductImage $productImage): void
    {
        // Hapus file saat record dihapus
        if ($productImage->path) {
            Storage::disk('public')->delete($productImage->path);
        }
    }

    /**
     * Handle the ProductImage "force deleted" event.
     */
    public function forceDeleted(ProductImage $productImage): void
    {
        // Hapus file saat force delete
        if ($productImage->path) {
            Storage::disk('public')->delete($productImage->path);
        }
    }
}
