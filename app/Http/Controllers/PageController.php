<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the specified page by slug
     */
    public function show(string $slug): View
    {
        $page = Page::active()
            ->bySlug($slug)
            ->firstOrFail();

        // Determine which view to use based on template
        $view = match ($page->template) {
            'faq' => 'pages.faq',
            'contact' => 'pages.contact',
            default => 'pages.default',
        };

        return view($view, compact('page'));
    }
}
