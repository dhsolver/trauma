<?php

namespace App\Http\Controllers;

use App\StaticPage;

class HomeController extends Controller {
    public function index()
    {
        $staticPage = StaticPage::where('slug', '/')->first();
        return view('pages.home', compact('staticPage'));
    }
}
