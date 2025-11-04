<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('brand.name')
                    ->label('Brand')
                    ->placeholder('-'),
                TextEntry::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('weight_gram')
                    ->numeric(),
                TextEntry::make('length_mm')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('width_mm')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('height_mm')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('sale_price')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('currency'),
                TextEntry::make('meta_title')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed()),
            ]);
    }
}
