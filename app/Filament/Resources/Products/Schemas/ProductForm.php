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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pilih produk yang tersedia di pusat katalog')
                    ->description('Atur informasi dasar, harga, stok, dan atribut produk untuk ditampilkan di website dari data pusat katalog yang kamu pilih.')
                    ->schema([
                        Select::make('catalog_product_id')
                            ->label('Pilih Produk dari Katalog')
                            ->relationship('product_inventory', 'sku', fn ($query) => $query->whereIsActive(true))
                            ->searchable(['sku', 'name'])
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "SKU: {$record->sku} | Nama: {$record->name}")
                            ->preload()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $catalogProduct = \App\Models\Inventory\Product::with([
                                        'imagesPrimary',
                                        'variants',
                                        'variantStocks',
                                    ])
                                        ->where('sku', $state)
                                        ->first();

                                    if ($catalogProduct) {
                                        // Auto-fill basic info dari katalog
                                        $set('name', $catalogProduct->name);
                                        $set('slug', Str::slug($catalogProduct->name));
                                        $set('sku', $catalogProduct->sku);
                                        $quantities = array_column($catalogProduct->variantStocks->toArray(), 'qty');
                                        $total_qty = array_sum($quantities);
                                        $set('stock', $total_qty ?? 0);

                                        // Description
                                        $set('description', $catalogProduct->description);

                                        // Short description dengan info brand & model
                                        $shortDesc = [];
                                        if ($catalogProduct->brand) {
                                            $shortDesc[] = 'Brand: '.$catalogProduct->brand;
                                        }
                                        if ($catalogProduct->model) {
                                            $shortDesc[] = 'Model: '.$catalogProduct->model;
                                        }
                                        if (! empty($shortDesc)) {
                                            $set('short_description', implode(' | ', $shortDesc));
                                        }

                                        // Brand - cari atau buat jika ada brand di catalog
                                        if ($catalogProduct->brand) {
                                            $brand = \App\Models\Brand::firstOrCreate(
                                                ['name' => Str::title($catalogProduct->brand)],
                                                [
                                                    'slug' => Str::slug($catalogProduct->brand),
                                                    'is_active' => true,
                                                ]
                                            );
                                            $set('brand_id', $brand->id);
                                        }

                                        // Auto-populate variants dari catalog
                                        if ($catalogProduct->variants && $catalogProduct->variants->isNotEmpty()) {
                                            $variantsData = [];
                                            foreach ($catalogProduct->variants as $variant) {
                                                $variantsData[] = [
                                                    'sku' => $variant->sku_variant,
                                                    'name' => ucfirst($variant->color).' - '.$variant->size,
                                                    'color' => strtolower($variant->color),
                                                    'size' => $variant->size,
                                                    'price' => $variant->price,
                                                    'sale_price' => null,
                                                    'weight_gram' => 0,
                                                    'options' => [],
                                                    'is_active' => $variant->status === 'active',
                                                ];
                                            }
                                            $set('variants', $variantsData);

                                            // Set default price dari variant pertama
                                            $firstVariant = $catalogProduct->variants->first();
                                            $set('price', $firstVariant->price);
                                            $set('stock', 0); // Will be managed by variants
                                        }

                                        // Note: Images tidak di auto-populate karena harus di-upload manual
                                        // User perlu upload gambar sendiri setelah memilih catalog

                                        // Meta untuk SEO
                                        $set('meta_title', $catalogProduct->name);
                                        $set('meta_description', Str::limit($catalogProduct->description, 160));
                                    }
                                }
                            })
                            ->helperText('Pilih produk yang sudah ada di pusat katalog untuk mengisi data produk ini secara otomatis'),
                        Toggle::make('highlights')
                            ->label('Produk Unggulan')
                            ->default(false)
                            ->helperText('Jika diaktifkan, semua data produk unggulan akan ditampilkan di homepage website'),
                    ]),
                Section::make('Informasi Dasar')
                    ->description('Informasi utama produk')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->readOnly(fn (callable $get) => (bool) $get('catalog_product_id'))
                            ->helperText('Nama produk yang akan ditampilkan kepada pelanggan'),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('categories', 'name')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Pilih kategori produk'),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'products',
                                column: 'slug',
                                ignoreRecord: true,
                                modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule) => $rule->whereNull('deleted_at')
                            )
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->readOnly(fn (callable $get) => (bool) $get('catalog_product_id'))
                            ->helperText('URL ramah SEO, akan dibuat otomatis dari nama produk'),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(
                                table: 'products',
                                column: 'sku',
                                ignoreRecord: true,
                                modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule) => $rule->whereNull('deleted_at')
                            )
                            ->maxLength(100)
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->readOnly(fn (callable $get) => (bool) $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Pilih brand/merek produk'),
                    ])->columns(2),

                Section::make('Deskripsi Produk')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->maxLength(500)
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Harga jual normal produk'),

                        TextInput::make('sale_price')
                            ->label('Harga Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->lte('price')
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Harga setelah diskon (opsional). Harus lebih kecil dari harga normal'),

                        TextInput::make('stock')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Berat produk dalam gram untuk perhitungan ongkir'),

                        TextInput::make('length_mm')
                            ->label('Panjang (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Panjang produk dalam milimeter'),

                        TextInput::make('width_mm')
                            ->label('Lebar (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Lebar produk dalam milimeter'),

                        TextInput::make('height_mm')
                            ->label('Tinggi (mm)')
                            ->numeric()
                            ->suffix('mm')
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Draft: Tidak tampil | Aktif: Tampil di website | Arsip: Disembunyikan'),

                        Toggle::make('is_featured')
                            ->label('Produk Unggulan')
                            ->default(false)
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Tampilkan di bagian produk unggulan homepage'),
                    ])->columns(2),

                Section::make('Atribut Tambahan')
                    ->schema([
                        KeyValue::make('attributes')
                            ->label('Atribut Produk')
                            ->addButtonLabel('Tambah Atribut')
                            ->keyLabel('Nama Atribut')
                            ->valueLabel('Nilai')
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
                            ->helperText('Judul untuk SEO, maksimal 60 karakter. Kosongkan untuk gunakan nama produk'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->disabled(fn (callable $get) => ! $get('catalog_product_id'))
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
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
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
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Deskripsi gambar untuk SEO dan aksesibilitas'),

                                Toggle::make('is_thumbnail')
                                    ->label('Jadikan Thumbnail Utama')
                                    ->default(false)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Gambar yang ditampilkan di daftar produk'),

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
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
                Section::make('Varian Produk')
                    ->description('Kelola varian produk seperti ukuran, warna, dll.')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship('variants')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Varian')
                                    ->maxLength(255)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Contoh: Merah - L, Biru - M (opsional)'),

                                TextInput::make('sku')
                                    ->label('SKU Varian')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Kode unik untuk varian ini'),

                                TextInput::make('color')
                                    ->label('Warna')
                                    ->maxLength(100)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Tentukan warna varian (opsional)'),

                                Select::make('size')
                                    ->label('Ukuran')
                                    ->options([
                                        'XS' => 'XS',
                                        'S' => 'S',
                                        'M' => 'M',
                                        'L' => 'L',
                                        'XL' => 'XL',
                                        'XXL' => 'XXL',
                                        '3XL' => '3XL',
                                        '4XL' => '4XL',
                                    ])
                                    ->searchable()
                                    ->native(false)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Pilih ukuran varian'),

                                KeyValue::make('options')
                                    ->label('Opsi Tambahan')
                                    ->addButtonLabel('Tambah Opsi')
                                    ->keyLabel('Nama Opsi')
                                    ->valueLabel('Nilai')
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Atribut tambahan lainnya (opsional)'),

                                TextInput::make('weight_gram')
                                    ->label('Berat (gram)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('gram')
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Berat varian dalam gram (opsional)'),

                                TextInput::make('price')
                                    ->label('Harga Varian')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Harga jual untuk varian ini'),

                                TextInput::make('sale_price')
                                    ->label('Harga Diskon Varian')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->lte('price')
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Opsional. Harus lebih kecil dari harga varian'),

                                Toggle::make('is_active')
                                    ->label('Aktifkan Varian')
                                    ->default(true)
                                    ->disabled(fn (callable $get) => ! $get('../../catalog_product_id'))
                                    ->helperText('Tentukan apakah varian ini tersedia untuk dijual'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Varian')
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => ($state['name'] ?? null)
                                ? $state['name']
                                : (($state['color'] ?? '').' '.($state['size'] ?? '') ?: 'Varian Produk')
                            )
                            ->cloneable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
