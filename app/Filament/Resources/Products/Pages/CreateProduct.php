<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $produk = static::getModel()::create($data);

        if (! empty($data['images'])) {
            $images = collect($data['images'])
                ->map(function ($imagePath, $index) {
                    return [
                        'path' => $imagePath,
                        'alt_text' => null,
                        'is_thumbnail' => $index === 0,
                        'sort_order' => $index,
                    ];
                })->all();

            $produk->images()->createMany($images);
        }

        return $produk;
    }
}
