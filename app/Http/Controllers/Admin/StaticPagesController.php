<?php

namespace App\Http\Controllers\Admin;

use App\StaticPage;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\StaticPageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class StaticPagesController extends AdminController {
    public function index() {
        $pages = StaticPage::all();
        return view('admin.staticpages.index', compact('pages', 'users'));
    }

    public function edit(StaticPage $staticPage)
    {
        $s3Data = prepareS3Data();

        return view('admin.staticpages.edit', compact('staticPage', 's3Data'));
    }

    public function update(StaticPageRequest $request, StaticPage $staticPage)
    {
        $staticPage->update($request->except('fileKeys', 'fileNames'));
        if (!empty($request->fileKeys)) {
            $staticPage->image = $request->fileKeys[0];
            $staticPage->save();
        }

        session()->flash('pageMessage', 'Static page has been updated!');
        return redirect()->action('Admin\StaticPagesController@edit', $staticPage);
    }
}
