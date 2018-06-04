<?php
use Illuminate\Database\Seeder;
use App\StaticPage;

class StaticPageTableSeeder extends Seeder {
    public function run()
    {
        DB::table('static_pages')->delete();

        $page = new StaticPage();
        $page->slug = '/';
        $page->title = 'Home';
        $page->save();

        $page = new StaticPage();
        $page->slug = '/consulting';
        $page->title = 'Consulting';
        $page->subtitle = 'Focused Trauma Solutions';
        $page->image = 'page-consulting.jpg';
        $page->save();

        $page = new StaticPage();
        $page->slug = '/data';
        $page->title = 'Data';
        $page->subtitle = 'Quantitative Analysis & Information Visualization Solutions';
        $page->image = 'page-data.jpg';
        $page->save();

        $page = new StaticPage();
        $page->slug = '/education';
        $page->title = 'Education';
        $page->subtitle = 'Specialized Continuing Education Solutions';
        $page->image = 'page-education.png';
        $page->save();

        $page = new StaticPage();
        $page->slug = '/terms';
        $page->title = 'Terms and Policies';
        $page->subtitle = 'Terms and Policies';
        $page->save();
    }
}
