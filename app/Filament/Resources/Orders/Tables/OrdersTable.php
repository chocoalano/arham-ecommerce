<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('voucher.id')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->searchable(),
                TextColumn::make('billingAddress.id')
                    ->searchable(),
                TextColumn::make('shippingAddress.id')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_courier')
                    ->searchable(),
                TextColumn::make('shipping_service')
                    ->searchable(),
                TextColumn::make('shipping_cost')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_etd')
                    ->searchable(),
                TextColumn::make('weight_total_gram')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('placed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('source')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
