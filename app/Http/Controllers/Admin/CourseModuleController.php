<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseModule;
// use App\User;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseModuleRequest;
use App\Http\Requests\Admin\CourseModuleDocumentRequest;
use Illuminate\Database\QueryException;

class CourseModuleController extends AdminController {
    public function create(Course $course) {
        return view('admin.coursemodules.create', compact('course'));
    }

    public function store(Course $course, CourseModuleRequest $request)
    {
        $courseModule = new CourseModule($request->except('addnew'));
        $courseModule->course_id = $course->id;
        $courseModule->save();

        session()->flash('courseModuleMessage', 'Course module has been created!');
        if ($request->addnew == '1') {
            return redirect()->action('Admin\CourseModuleController@create', $course);
        }
        return redirect()->action('Admin\CourseModuleController@edit', [
            'course' => $course,
            'courseModule' => $courseModule
        ]);
    }

    public function edit(Course $course, CourseModule $courseModule)
    {
        $s3Data = prepareS3Data();
        return view('admin.coursemodules.edit', compact('course', 'courseModule', 's3Data'));
    }

    public function show(Course $course, CourseModule $courseModule)
    {
        $courseModule->is_visible = true;
        $courseModule->update();
        session()->flash('courseMessage', 'Course module has been set visible!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function hide(Course $course, CourseModule $courseModule)
    {
        $courseModule->is_visible = false;
        $courseModule->update();
        session()->flash('courseMessage', 'Course module has been set hidden!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function update(Course $course, CourseModuleRequest $request, CourseModule $courseModule)
    {
        $courseModule->update($request->except('addnew'));

        session()->flash('courseModuleMessage', 'Course module has been updated!');
        if ($request->addnew == '1') {
            return redirect()->action('Admin\CourseModuleController@create', $course);
        }
        return redirect()->action('Admin\CourseModuleController@edit', [
            'course' => $course,
            'courseModule' => $courseModule
        ]);
    }

    public function delete(Course $course, CourseModule $courseModule)
    {
        try {
            $courseModule->delete();
            session()->flash('courseMessage', 'Course module has been deleted!');
        } catch (QueryException $e) {
            session()->flash('message', 'Course module can not be deleted!');
        }
        return redirect()->action('Admin\CourseController@edit', $course);
    }
}
