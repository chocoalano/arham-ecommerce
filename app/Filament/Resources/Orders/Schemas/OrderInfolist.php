<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('customer.name')
                    ->label('Customer')
                    ->placeholder('-'),
                TextEntry::make('voucher.id')
                    ->label('Voucher')
                    ->placeholder('-'),
                TextEntry::make('customer_name'),
                TextEntry::make('customer_email')
                    ->placeholder('-'),
                TextEntry::make('customer_phone')
                    ->placeholder('-'),
                TextEntry::make('billingAddress.id')
                    ->label('Billing address')
                    ->placeholder('-'),
                TextEntry::make('billing_address_snapshot')
                    ->columnSpanFull(),
                TextEntry::make('shippingAddress.id')
                    ->label('Shipping address')
                    ->placeholder('-'),
                TextEntry::make('shipping_address_snapshot')
                    ->columnSpanFull(),
                TextEntry::make('currency'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('discount_total')
                    ->numeric(),
                TextEntry::make('tax_total')
                    ->numeric(),
                TextEntry::make('shipping_total')
                    ->numeric(),
                TextEntry::make('grand_total')
                    ->numeric(),
                TextEntry::make('shipping_courier')
                    ->placeholder('-'),
                TextEntry::make('shipping_service')
                    ->placeholder('-'),
                TextEntry::make('shipping_cost')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('shipping_etd')
                    ->placeholder('-'),
                TextEntry::make('weight_total_gram')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('placed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('cancelled_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('source'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
