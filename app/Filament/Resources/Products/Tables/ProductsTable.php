<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

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
                    BulkAction::make('set_active')
                        ->label('Set Active')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->status = 'active';
                                $record->save();

                                // Also activate variants
                                $record->variants()->update(['is_active' => true]);
                                $count++;
                            }

                            Notification::make()
                                ->success()
                                ->title('Products Activated')
                                ->body("{$count} product(s) and their variants have been set to active.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('set_inactive')
                        ->label('Set Inactive')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->status = 'archived';
                                $record->save();

                                // Also deactivate variants
                                $record->variants()->update(['is_active' => false]);
                                $count++;
                            }

                            Notification::make()
                                ->success()
                                ->title('Products Deactivated')
                                ->body("{$count} product(s) and their variants have been set to inactive.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                // Soft delete variants first
                                $record->variants()->delete();
                                // Then soft delete the product
                                $record->delete();
                            }
                        }),
                    ForceDeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                // Force delete variants first
                                $record->variants()->forceDelete();
                                // Then force delete the product
                                $record->forceDelete();
                            }
                        }),
                    RestoreBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                // Restore the product
                                $record->restore();
                                // Restore variants
                                $record->variants()->restore();
                            }
                        }),
                ]),
            ]);
    }
}
