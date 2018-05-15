<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseModule;
// use App\User;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseModuleRequest;
use App\Http\Requests\Admin\CourseModuleDocumentRequest;

class CourseModuleController extends AdminController {

    public function __construct()
    {
    }

    public function create(Course $course) {
        return view('admin.coursemodules.create', compact('course'));
    }

    public function store(Course $course, CourseModuleRequest $request)
    {
        $courseModule = new CourseModule($request->all());
        $courseModule->course_id = $course->id;
        $courseModule->save();

        session()->flash('courseModuleMessage', 'Course module has been created!');
        return redirect()->action('Admin\CourseModuleController@edit', [
            'course' => $course,
            'courseModule' => $courseModule
        ]);
    }

    public function edit(Course $course, CourseModule $courseModule)
    {
        return view('admin.coursemodules.edit', compact('course', 'courseModule'));
    }

    public function update(Course $course, CourseModuleRequest $request, CourseModule $courseModule)
    {
        $courseModule->update($request->all());

        session()->flash('courseModuleMessage', 'Course module has been updated!');
        return redirect()->action('Admin\CourseModuleController@edit', [
            'course' => $course,
            'courseModule' => $courseModule
        ]);
    }

    public function delete(Course $course, CourseModule $courseModule)
    {
        $courseModule->delete();
        session()->flash('courseMessage', 'Course module has been deleted!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }
}
