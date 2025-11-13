<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->description('Informasi utama produk')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->helperText('Nama produk yang akan ditampilkan kepada pelanggan'),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('categories', 'name')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->helperText('Pilih kategori produk'),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL ramah SEO, akan dibuat otomatis dari nama produk'),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->helperText('Kode unik produk untuk identifikasi stok'),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Brand')
                                    ->required(),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                            ])
                            ->helperText('Pilih brand/merek produk'),
                    ])->columns(2),

                Section::make('Deskripsi Produk')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Ringkasan produk maksimal 500 karakter, ditampilkan di halaman daftar produk'),

                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                                'h2',
                                'h3',
                            ])
                            ->helperText('Deskripsi detail produk dengan format lengkap'),
                    ]),

                Section::make('Harga & Stok')
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga Normal')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Harga jual normal produk'),

                        TextInput::make('sale_price')
                            ->label('Harga Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->lte('price')
                            ->helperText('Harga setelah diskon (opsional). Harus lebih kecil dari harga normal'),

                        TextInput::make('stock')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Jumlah stok tersedia. Stok akan berkurang otomatis saat ada pembelian'),

                        TextInput::make('currency')
                            ->label('Mata Uang')
                            ->required()
                            ->default('IDR')
                            ->disabled()
                            ->helperText('Mata uang yang digunakan'),
                    ])->columns(2),

                Section::make('Dimensi & Berat')
                    ->description('Untuk perhitungan ongkos kirim')
                    ->schema([
                        TextInput::make('weight_gram')
                            ->label('Berat (gram)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('gram')
                            ->helperText('Berat produk dalam gram untuk perhitungan ongkir'),

                        TextInput::make('length_mm')
                            ->label('Panjang (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->helperText('Panjang produk dalam milimeter'),

                        TextInput::make('width_mm')
                            ->label('Lebar (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->helperText('Lebar produk dalam milimeter'),

                        TextInput::make('height_mm')
                            ->label('Tinggi (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->helperText('Tinggi produk dalam milimeter'),
                    ])->columns(4),

                Section::make('Status & Tampilan')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'archived' => 'Arsip',
                            ])
                            ->default('active')
                            ->required()
                            ->helperText('Draft: Tidak tampil | Aktif: Tampil di website | Arsip: Disembunyikan'),

                        Toggle::make('is_featured')
                            ->label('Produk Unggulan')
                            ->default(false)
                            ->helperText('Tampilkan di bagian produk unggulan homepage'),
                    ])->columns(2),

                Section::make('Atribut Tambahan')
                    ->schema([
                        KeyValue::make('attributes')
                            ->label('Atribut Produk')
                            ->addButtonLabel('Tambah Atribut')
                            ->keyLabel('Nama Atribut')
                            ->valueLabel('Nilai')
                            ->helperText('Contoh: Warna → Merah, Ukuran → XL, Material → Katun'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('SEO')
                    ->description('Optimasi untuk mesin pencari')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Judul untuk SEO, maksimal 60 karakter. Kosongkan untuk gunakan nama produk'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Deskripsi untuk mesin pencari, maksimal 160 karakter'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Gambar Produk')
                    ->description('Kelola gambar produk yang akan ditampilkan di website')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Gambar')
                                    ->directory('products')
                                    ->disk('public')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        null,
                                        '27:28',
                                        '108:53',
                                        '51:52',
                                        '99:119',
                                    ])
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state instanceof \Illuminate\Http\UploadedFile) {
                                            $imageService = app(\App\Services\ImageProcessingService::class);
                                            $paths = $imageService->uploadWithRatios($state, 'products', 'public');

                                            $set('path', $paths['original']);
                                            $set('path_ratio_27_28', $paths['ratio_27_28']);
                                            $set('path_ratio_108_53', $paths['ratio_108_53']);
                                            $set('path_ratio_51_52', $paths['ratio_51_52']);
                                            $set('path_ratio_99_119', $paths['ratio_99_119']);
                                        }
                                    })
                                    ->helperText('Format: JPG, PNG, WEBP. Maksimal 5MB. Akan di-generate 4 ratio berbeda'),

                                TextInput::make('path_ratio_27_28')
                                    ->label('Ratio 27:28 (540×560)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Large banner - Auto-generated'),

                                TextInput::make('path_ratio_108_53')
                                    ->label('Ratio 108:53 (540×265)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Wide banner - Auto-generated'),

                                TextInput::make('path_ratio_51_52')
                                    ->label('Ratio 51:52 (255×260)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Small square - Auto-generated'),

                                TextInput::make('path_ratio_99_119')
                                    ->label('Ratio 99:119 (198×238)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Small portrait - Auto-generated'),

                                TextInput::make('alt_text')
                                    ->label('Teks Alternatif')
                                    ->maxLength(100)
                                    ->helperText('Deskripsi gambar untuk SEO dan aksesibilitas'),

                                Toggle::make('is_thumbnail')
                                    ->label('Jadikan Thumbnail Utama')
                                    ->default(false)
                                    ->helperText('Gambar yang ditampilkan di daftar produk'),

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Urutan tampilan gambar (0 = pertama)'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->orderColumn('sort_order')
                            ->addActionLabel('Tambah Gambar')
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['alt_text'] ?? 'Gambar Produk')
                            ->cloneable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
