<?php

namespace App\Filament\Resources\Shipments;

use App\Filament\Resources\Shipments\Pages\ManageShipments;
use App\Models\Shipment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationLabel = 'Pengiriman';

    protected static ?string $pluralLabel = 'Pengiriman';

    protected static string|UnitEnum|null $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                TextInput::make('courier')
                    ->required(),
                TextInput::make('service'),
                TextInput::make('waybill'),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('etd'),
                DateTimePicker::make('shipped_at'),
                DateTimePicker::make('delivered_at'),
                TextInput::make('receiver_name'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'packed' => 'Packed',
                        'shipped' => 'Shipped',
                        'in_transit' => 'In transit',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                TextInput::make('raw_response'),
                TextInput::make('origin_id')
                    ->numeric(),
                TextInput::make('destination_id')
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('courier'),
                TextEntry::make('service')
                    ->placeholder('-'),
                TextEntry::make('waybill')
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('etd')
                    ->placeholder('-'),
                TextEntry::make('shipped_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('delivered_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('receiver_name')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('origin_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('destination_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->searchable(),
                TextColumn::make('courier')
                    ->searchable(),
                TextColumn::make('service')
                    ->searchable(),
                TextColumn::make('waybill')
                    ->searchable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('etd')
                    ->searchable(),
                TextColumn::make('shipped_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('receiver_name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('origin_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('destination_id')
                    ->numeric()
                    ->sortable(),
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageShipments::route('/'),
        ];
    }
}
