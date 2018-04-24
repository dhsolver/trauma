<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseRequest;

use Illuminate\Support\Facades\Auth;


class CourseController extends AdminController {

    public function __construct()
    {
    }

    public function index() {
        return view('admin.courses.index');
    }

    public function create() {
        return view('admin.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $course = new Course($request->except('photo'));
        // $article -> user_id = Auth::id();

        $photo = '';
        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file -> getClientOriginalExtension();
            $photo = sha1($filename . time()) . '.' . $extension;
        }
        $course->photo = $photo;
        $course->save();

        if($request->hasFile('photo'))
        {
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/';
            $request->file('photo')->move($destinationPath, $photo);
        }

        return redirect()->action('Admin\CourseController@index');
    }
}
