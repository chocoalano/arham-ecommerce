<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }
}
