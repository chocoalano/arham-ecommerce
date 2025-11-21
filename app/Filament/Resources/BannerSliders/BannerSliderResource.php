<?php

namespace App\Filament\Resources\BannerSliders;

use App\Filament\Resources\BannerSliders\Pages\ManageBannerSliders;
use App\Models\BannerSlider;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerSliderResource extends Resource
{
    protected static ?string $model = BannerSlider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'BannerSlider';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('button_text')
                    ->required()
                    ->default('Belanja Sekarang'),
                TextInput::make('link_url')
                    ->url()
                    ->required(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('banner_sliders')
                    ->required()
                    ->helperText('Gambar utama dengan rasio 16:9'),
                FileUpload::make('image_path_108_53')
                    ->image()
                    ->disk('public')
                    ->directory('banner_sliders')
                    ->helperText('Opsional: Gambar dengan rasio 108:53 untuk tampilan responsif'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('discount_percent')
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('button_text'),
                TextEntry::make('link_url'),
                ImageEntry::make('image_path')->disk('public'),
                ImageEntry::make('image_path_108_53')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('sort_order')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('discount_percent')
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
            ->recordTitleAttribute('BannerSlider')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('button_text')
                    ->searchable(),
                TextColumn::make('link_url')
                    ->searchable(),
                ImageColumn::make('image_path')->disk('public'),
                ImageColumn::make('image_path_108_53')->disk('public'),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('discount_percent')
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
            'index' => ManageBannerSliders::route('/'),
        ];
    }
}
