<?php

namespace App\Filament\Resources\Vouchers;

use App\Filament\Resources\Vouchers\Pages\ManageVouchers;
use App\Models\Voucher;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationLabel = 'Voucher';

    protected static ?string $pluralLabel = 'Voucher';

    protected static string|UnitEnum|null $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                Select::make('type')
                    ->options(['percent' => 'Percent', 'fixed' => 'Fixed', 'free_shipping' => 'Free shipping'])
                    ->default('percent')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('max_discount')
                    ->numeric(),
                TextInput::make('min_subtotal')
                    ->numeric(),
                TextInput::make('usage_limit')
                    ->numeric(),
                TextInput::make('used_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('valid_from'),
                DateTimePicker::make('valid_until'),
                TextInput::make('applicable'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('value')
                    ->numeric(),
                TextEntry::make('max_discount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('min_subtotal')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('usage_limit')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('used_count')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('valid_from')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('valid_until')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Voucher $record): bool => $record->trashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_discount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('min_subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('used_count')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('valid_from')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('valid_until')
                    ->dateTime()
                    ->sortable(),
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
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageVouchers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
