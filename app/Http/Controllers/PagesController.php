<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
   
    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function download()
    {
        return view('pages.download');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function documentation()
    {
        return view('pages.documentation');
    }
    public function reviews()
    {
        return view('pages.reviews');
    }

}
