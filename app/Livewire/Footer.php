<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use Livewire\Component;

class Footer extends Component
{
    public $email = '';

    public $footerBlocks = [];

    public $socialLinks = [];

    public function mount(): void
    {
        $this->footerBlocks = SiteSetting::get('footer_blocks', [
            ['title' => 'Butuh bantuan?', 'content' => 'Call: 1-800-345-6789'],
            ['title' => 'Produk & Penjualan', 'content' => 'Call: 1-877-345-6789'],
            ['title' => 'Keuntungan Belanja Sekarang:', 'content' => 'Nikmati layanan pengiriman gratis ke seluruh Indonesia* dan kemudahan pengembalian barang dalam 30 hari. **Belanja tanpa khawatir!**'],
        ]);

        $this->socialLinks = SiteSetting::get('footer_social_links', [
            ['url' => '//www.twitter.com', 'icon' => 'fa-twitter', 'name' => 'Twitter'],
            ['url' => '//www.rss.com', 'icon' => 'fa-rss', 'name' => 'RSS'],
            ['url' => '//plus.google.com', 'icon' => 'fa-google-plus', 'name' => 'Google Plus'],
            ['url' => '//www.facebook.com', 'icon' => 'fa-facebook', 'name' => 'Facebook'],
            ['url' => '//www.youtube.com', 'icon' => 'fa-youtube', 'name' => 'YouTube'],
            ['url' => '//www.instagram.com', 'icon' => 'fa-instagram', 'name' => 'Instagram'],
        ]);
    }

    public function subscribe(): void
    {
        $this->validate([
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.unique' => 'This email is already subscribed to our newsletter.',
        ]);

        try {
            \App\Models\Newsletter::create([
                'email' => $this->email,
                'status' => 'active',
            ]);

            $this->dispatch('newsletter-subscribed', email: $this->email);
            $this->reset('email');

            session()->flash('newsletter_success', 'Thank you for subscribing to our newsletter!');
        } catch (\Exception $e) {
            session()->flash('newsletter_error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        $footerPages = Page::footer()->get();

        $kategoriData = ProductCategory::whereIsActive(true)->take(5)->get();
        $productsLinks = $kategoriData->map(function ($kategori) {
            return [
                'label' => $kategori->name,
                'url' => route('catalog.index', ['category' => $kategori->slug]),
            ];
        })->toArray();

        $companyLinks = $footerPages->map(function ($page) {
            return [
                'label' => $page->title,
                'url' => $page->slug === 'about' ? route('about') : route('page.show', $page->slug),
            ];
        })->toArray();

        return view('livewire.footer', [
            'footerPages' => $footerPages,
            'productsLinks' => $productsLinks,
            'companyLinks' => $companyLinks,
            'copyrightText' => SiteSetting::get('footer_copyright_text', 'Arham E-Commerce'),
            'newsletterTitle' => SiteSetting::get('footer_newsletter_title', 'Berlangganan'),
            'newsletterPlaceholder' => SiteSetting::get('footer_newsletter_placeholder', 'Alamat email kamu'),
            'addressTitle' => SiteSetting::get('footer_address_title', 'Alamat'),
            'productColumnTitle' => SiteSetting::get('footer_product_column_title', 'Produk'),
            'companyColumnTitle' => SiteSetting::get('footer_company_column_title', 'Perusahaan Kami'),
            'socialSectionTitle' => SiteSetting::get('footer_social_section_title', 'Ikuti Kami:'),
        ]);
    }
}
