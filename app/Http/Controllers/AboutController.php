<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
