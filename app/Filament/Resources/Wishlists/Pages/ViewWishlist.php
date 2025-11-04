<?php

namespace App\Filament\Resources\Wishlists\Pages;

use App\Filament\Resources\Wishlists\WishlistResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWishlist extends ViewRecord
{
    protected static string $resource = WishlistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
