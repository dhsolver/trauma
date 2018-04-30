<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CourseController extends AdminController {

    public function __construct()
    {
    }

    public function index(Request $request) {

        if (empty($request->title)) {
            $courses = Course::where('id', '>', 0);
        } else {
            $courses = Course::where('title', 'like', "%$request->title%");
        }
        if (!empty($request->objective)) $courses = $courses->where('objective', 'like', "%$request->objective%");

        $courses = $courses->orderBy('title', 'asc')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function create() {
        return view('admin.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $course = new Course($request->except('photo', 'online_only'));

        $photo = '';
        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file -> getClientOriginalExtension();
            $photo = sha1($filename . time()) . '.' . $extension;
        }
        $course->photo = $photo;
        if ($request->online_only == '1') {
            $course->online_only = true;
        } else {
            $course->online_only = false;
        }
        $course->save();

        if($request->hasFile('photo'))
        {
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/';
            $request->file('photo')->move($destinationPath, $photo);
        }

        session()->flash('courseMessage', 'Course has been created!');
        return redirect()->action('Admin\CourseController@index');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $photo = '';
        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file -> getClientOriginalExtension();
            $photo = sha1($filename . time()) . '.' . $extension;
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/';
            $request->file('photo')->move($destinationPath, $photo);
        }
        $course->photo = $photo;
        $course->update($request->except('photo', 'online_only'));
        if ($request->online_only == '1') {
            $course->online_only = true;
        } else {
            $course->online_only = false;
            if ($course->date_start > $course->date_end) {
                return redirect()->action('Admin\CourseController@edit', $course)
                            ->withInput();
            }
        }
        $course->save();

        session()->flash('courseMessage', 'Course has been updated!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function delete(Course $course)
    {
        $course->delete();
        session()->flash('courseMessage', 'Course has been deleted!');
        return redirect()->action('Admin\CourseController@index');
    }
}
