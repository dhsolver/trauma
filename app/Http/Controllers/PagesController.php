<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StaticPage;
use Illuminate\Http\Request;

class PagesController extends Controller {

    public function welcome()
    {
        return view('pages.welcome');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function showStaticPage()
    {
        $staticPage = StaticPage::where('slug', '/'.request()->path())->first();
        return view('pages.staticpage', compact('staticPage'));
    }
}
