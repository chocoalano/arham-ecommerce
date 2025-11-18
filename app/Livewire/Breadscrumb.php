<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Livewire\Component;

class Breadscrumb extends Component
{
    // Properti publik untuk menyimpan array breadcrumb
    public $breadcrumbs = [];

    // Metode mount dijalankan saat komponen diinisialisasi
    public function mount()
    {
        // Ambil path URL saat ini (misal: 'products/detail/123')
        $path = Request::path();

        // 1. Inisialisasi dengan link Home
        $this->breadcrumbs[] = [
            'title' => 'Home',
            'url' => url('/'),
            'active' => $path === '/',
        ];

        // Filter dan pecah path menjadi segmen
        $segments = array_filter(explode('/', $path));
        $url = '';

        // 2. Iterasi untuk membuat breadcrumb per segmen
        foreach ($segments as $index => $segment) {
            // Bangun URL secara kumulatif
            $url .= '/'.$segment;

            // Konversi slug (misal: 'about-us') menjadi judul yang rapi ('About Us')
            $title = Str::title(str_replace(['-', '_'], ' ', $segment));

            // Cek apakah ini segmen terakhir (item aktif)
            $active = $index === count($segments) - 1;

            $this->breadcrumbs[] = [
                'title' => $title,
                'url' => url($url),
                'active' => $active,
            ];
        }
    }

    public function render()
    {
        return view('livewire.breadscrumb');
    }
}
