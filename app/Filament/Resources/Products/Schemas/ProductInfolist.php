<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Produk')
                            ->weight('bold'),

                        TextEntry::make('sku')
                            ->label('SKU')
                            ->copyable()
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('slug')
                            ->label('Slug URL')
                            ->copyable()
                            ->color('gray'),

                        TextEntry::make('brand.name')
                            ->label('Brand')
                            ->placeholder('-')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('categories.name')
                            ->label('Kategori')
                            ->badge()
                            ->separator(',')
                            ->placeholder('-'),

                        TextEntry::make('product_inventory.sku')
                            ->label('SKU Katalog')
                            ->placeholder('-')
                            ->copyable()
                            ->badge()
                            ->color('success'),
                    ])->columns(2),

                Section::make('Deskripsi')
                    ->schema([
                        TextEntry::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->placeholder('-'),

                        TextEntry::make('description')
                            ->label('Deskripsi Lengkap')
                            ->placeholder('-')
                            ->html(),
                    ])->collapsible(),

                Section::make('Harga & Stok')
                    ->schema([
                        TextEntry::make('price')
                            ->label('Harga Normal')
                            ->money('IDR')
                            ->weight('bold'),

                        TextEntry::make('sale_price')
                            ->label('Harga Diskon')
                            ->money('IDR')
                            ->placeholder('-')
                            ->color('success'),

                        TextEntry::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),

                        TextEntry::make('currency')
                            ->label('Mata Uang')
                            ->badge(),
                    ])->columns(2),

                Section::make('Dimensi & Berat')
                    ->schema([
                        TextEntry::make('weight_gram')
                            ->label('Berat')
                            ->numeric()
                            ->suffix(' gram'),

                        TextEntry::make('length_mm')
                            ->label('Panjang')
                            ->numeric()
                            ->suffix(' mm')
                            ->placeholder('-'),

                        TextEntry::make('width_mm')
                            ->label('Lebar')
                            ->numeric()
                            ->suffix(' mm')
                            ->placeholder('-'),

                        TextEntry::make('height_mm')
                            ->label('Tinggi')
                            ->numeric()
                            ->suffix(' mm')
                            ->placeholder('-'),
                    ])->columns(4)
                    ->collapsible(),

                Section::make('Status & Tampilan')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'draft' => 'warning',
                                'archived' => 'danger',
                                default => 'gray',
                            }),

                        IconEntry::make('is_featured')
                            ->label('Produk Unggulan')
                            ->boolean(),
                    ])->columns(2),

                Section::make('Atribut Tambahan')
                    ->schema([
                        KeyValueEntry::make('attributes')
                            ->label('Atribut Produk')
                            ->placeholder('-'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('SEO')
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('Meta Title')
                            ->placeholder('-'),

                        TextEntry::make('meta_description')
                            ->label('Meta Description')
                            ->placeholder('-'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Gambar Produk')
                    ->schema([
                        RepeatableEntry::make('images')
                            ->label('')
                            ->schema([
                                ImageEntry::make('path')
                                    ->label('Gambar')
                                    ->disk('public')
                                    ->height(150)
                                    ->defaultImageUrl(url('/images/placeholder.png')),

                                TextEntry::make('alt_text')
                                    ->label('Alt Text')
                                    ->placeholder('-'),

                                IconEntry::make('is_thumbnail')
                                    ->label('Thumbnail Utama')
                                    ->boolean(),

                                TextEntry::make('sort_order')
                                    ->label('Urutan')
                                    ->badge(),
                            ])
                            ->columns(4)
                            ->placeholder('Belum ada gambar'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Varian Produk')
                    ->schema([
                        RepeatableEntry::make('variants')
                            ->label('')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Varian')
                                    ->placeholder('-')
                                    ->weight('bold'),

                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->copyable()
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('color')
                                    ->label('Warna')
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('size')
                                    ->label('Ukuran')
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('weight_gram')
                                    ->label('Berat')
                                    ->numeric()
                                    ->suffix(' gram')
                                    ->placeholder('-'),

                                TextEntry::make('price')
                                    ->label('Harga')
                                    ->money('IDR')
                                    ->weight('bold'),

                                TextEntry::make('sale_price')
                                    ->label('Harga Diskon')
                                    ->money('IDR')
                                    ->placeholder('-')
                                    ->color('success'),

                                IconEntry::make('is_active')
                                    ->label('Aktif')
                                    ->boolean(),

                                KeyValueEntry::make('options')
                                    ->label('Opsi Tambahan')
                                    ->placeholder('-'),
                            ])
                            ->columns(3)
                            ->placeholder('Belum ada varian'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->dateTime('d M Y H:i')
                            ->visible(fn (Product $record): bool => $record->trashed())
                            ->color('danger'),
                    ])->columns(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
