<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class FooterSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Footer';

    protected static ?string $title = 'Pengaturan Footer';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.footer-settings';

    public ?array $footerBlocks = [];

    public ?array $socialLinks = [];

    public ?string $copyrightText = '';

    public ?string $newsletterTitle = '';

    public ?string $newsletterPlaceholder = '';

    public ?string $addressTitle = '';

    public ?string $productColumnTitle = '';

    public ?string $companyColumnTitle = '';

    public ?string $socialSectionTitle = '';

    public function mount(): void
    {
        $this->footerBlocks = SiteSetting::get('footer_blocks', [
            ['title' => 'Butuh bantuan?', 'content' => 'Call: 1-800-345-6789'],
            ['title' => 'Produk & Penjualan', 'content' => 'Call: 1-877-345-6789'],
            ['title' => 'Keuntungan Belanja Sekarang:', 'content' => 'Nikmati layanan pengiriman gratis ke seluruh Indonesia* dan kemudahan pengembalian barang dalam 30 hari. **Belanja tanpa khawatir!**'],
        ]);

        $this->socialLinks = SiteSetting::get('footer_social_links', [
            ['url' => '//www.twitter.com', 'icon' => 'fa-twitter', 'name' => 'Twitter'],
            ['url' => '//www.facebook.com', 'icon' => 'fa-facebook', 'name' => 'Facebook'],
            ['url' => '//www.instagram.com', 'icon' => 'fa-instagram', 'name' => 'Instagram'],
            ['url' => '//www.youtube.com', 'icon' => 'fa-youtube', 'name' => 'YouTube'],
        ]);

        $this->copyrightText = SiteSetting::get('footer_copyright_text', 'Arham E-Commerce');
        $this->newsletterTitle = SiteSetting::get('footer_newsletter_title', 'Berlangganan');
        $this->newsletterPlaceholder = SiteSetting::get('footer_newsletter_placeholder', 'Alamat email kamu');
        $this->addressTitle = SiteSetting::get('footer_address_title', 'Alamat');
        $this->productColumnTitle = SiteSetting::get('footer_product_column_title', 'Produk');
        $this->companyColumnTitle = SiteSetting::get('footer_company_column_title', 'Perusahaan Kami');
        $this->socialSectionTitle = SiteSetting::get('footer_social_section_title', 'Ikuti Kami:');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Footer Settings')
                    ->tabs([
                        Tab::make('Blok Informasi')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Repeater::make('footerBlocks')
                                    ->label('Blok Footer')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Judul')
                                            ->required(),
                                        Textarea::make('content')
                                            ->label('Konten')
                                            ->helperText('Mendukung format HTML sederhana seperti <b>, <i>, <a>')
                                            ->required()
                                            ->rows(3),
                                    ])
                                    ->defaultItems(1)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->columns(1),
                            ]),

                        Tab::make('Media Sosial')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('socialSectionTitle')
                                    ->label('Judul Bagian Sosial Media')
                                    ->default('Ikuti Kami:'),

                                Repeater::make('socialLinks')
                                    ->label('Link Media Sosial')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),
                                        TextInput::make('url')
                                            ->label('URL')
                                            ->required(),
                                        TextInput::make('icon')
                                            ->label('Icon Class (Font Awesome)')
                                            ->required()
                                            ->helperText('Contoh: fa-facebook, fa-instagram, fa-twitter, fa-youtube, fa-tiktok'),
                                    ])
                                    ->defaultItems(1)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->columns(3),
                            ]),

                        Tab::make('Judul Kolom')
                            ->icon('heroicon-o-view-columns')
                            ->schema([
                                TextInput::make('productColumnTitle')
                                    ->label('Judul Kolom Produk')
                                    ->default('Produk'),

                                TextInput::make('companyColumnTitle')
                                    ->label('Judul Kolom Perusahaan')
                                    ->default('Perusahaan Kami'),

                                TextInput::make('addressTitle')
                                    ->label('Judul Alamat')
                                    ->default('Alamat'),
                            ]),

                        Tab::make('Newsletter')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('newsletterTitle')
                                    ->label('Judul Newsletter')
                                    ->default('Berlangganan'),

                                TextInput::make('newsletterPlaceholder')
                                    ->label('Placeholder Email')
                                    ->default('Alamat email kamu'),
                            ]),

                        Tab::make('Copyright')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                TextInput::make('copyrightText')
                                    ->label('Teks Copyright')
                                    ->helperText('Akan ditampilkan sebagai: Copyright © ' . date('Y') . ' [teks ini]. All Rights Reserved')
                                    ->default('Arham E-Commerce'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        SiteSetting::set('footer_blocks', $this->footerBlocks, 'footer');
        SiteSetting::set('footer_social_links', $this->socialLinks, 'footer');
        SiteSetting::set('footer_copyright_text', $this->copyrightText, 'footer');
        SiteSetting::set('footer_newsletter_title', $this->newsletterTitle, 'footer');
        SiteSetting::set('footer_newsletter_placeholder', $this->newsletterPlaceholder, 'footer');
        SiteSetting::set('footer_address_title', $this->addressTitle, 'footer');
        SiteSetting::set('footer_product_column_title', $this->productColumnTitle, 'footer');
        SiteSetting::set('footer_company_column_title', $this->companyColumnTitle, 'footer');
        SiteSetting::set('footer_social_section_title', $this->socialSectionTitle, 'footer');

        Notification::make()
            ->title('Pengaturan footer berhasil disimpan!')
            ->success()
            ->send();
    }
}
