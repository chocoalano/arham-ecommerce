<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                Select::make('voucher_id')
                    ->relationship('voucher', 'id'),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_email')
                    ->email(),
                TextInput::make('customer_phone')
                    ->tel(),
                Select::make('billing_address_id')
                    ->relationship('billingAddress', 'id'),
                Textarea::make('billing_address_snapshot')
                    ->required()
                    ->columnSpanFull(),
                Select::make('shipping_address_id')
                    ->relationship('shippingAddress', 'id'),
                Textarea::make('shipping_address_snapshot')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('currency')
                    ->required()
                    ->default('IDR'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_courier'),
                TextInput::make('shipping_service'),
                TextInput::make('shipping_cost')
                    ->numeric(),
                TextInput::make('shipping_etd'),
                TextInput::make('weight_total_gram')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'awaiting_payment' => 'Awaiting payment',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                        'expired' => 'Expired',
                    ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('placed_at'),
                DateTimePicker::make('paid_at'),
                DateTimePicker::make('cancelled_at'),
                TextInput::make('source')
                    ->required()
                    ->default('web'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('meta'),
            ]);
    }
}
