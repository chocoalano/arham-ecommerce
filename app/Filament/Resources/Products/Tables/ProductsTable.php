<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.path')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->getStateUsing(function ($record) {
                        return $record->images->pluck('path')->toArray();
                    }),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->searchable(),
                TextColumn::make('weight_gram')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('length_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('width_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('height_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('meta_title')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
