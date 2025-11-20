<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\ProductCategory;
use Livewire\Component;

class Footer extends Component
{
    public $email = '';

    public $footerBlocks = [];

    public $socialLinks = [];

    public function mount(): void
    {
        // Footer blocks
        $this->footerBlocks = [
            [
                'title' => 'Butuh bantuan?',
                'content' => 'Call: 1-800-345-6789',
            ],
            [
                'title' => 'Produk & Penjualan',
                'content' => 'Call: 1-877-345-6789',
            ],
            [
                'title' => 'Keuntungan Belanja Sekarang:',
                'content' => 'Nikmati layanan pengiriman gratis ke seluruh Indonesia* dan kemudahan pengembalian barang
                                dalam 30 hari. **Belanja tanpa khawatir!**',
            ],
        ];

        // Social links
        $this->socialLinks = [
            ['url' => '//www.twitter.com', 'icon' => 'fa-twitter', 'name' => 'Twitter'],
            ['url' => '//www.rss.com', 'icon' => 'fa-rss', 'name' => 'RSS'],
            ['url' => '//plus.google.com', 'icon' => 'fa-google-plus', 'name' => 'Google Plus'],
            ['url' => '//www.facebook.com', 'icon' => 'fa-facebook', 'name' => 'Facebook'],
            ['url' => '//www.youtube.com', 'icon' => 'fa-youtube', 'name' => 'YouTube'],
            ['url' => '//www.instagram.com', 'icon' => 'fa-instagram', 'name' => 'Instagram'],
        ];
    }

    public function subscribe(): void
    {
        $this->validate([
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.unique' => 'This email is already subscribed to our newsletter.',
        ]);

        try {
            // Save to database
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
        // Get footer pages from database
        $footerPages = Page::footer()->get();

        // Group pages by category (you can customize this)
        $kategoriData = ProductCategory::whereIsActive(true)->take(5)->get();
        $productsLinks = $kategoriData->map(function ($kategori) {
            return [
                'label' => $kategori->name,
                'url' => route('catalog.index', ['category'=>$kategori->slug]),
            ];
        })->toArray();

        // Company links from Pages model
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
        ]);
    }
}
