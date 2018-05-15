<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseDocument;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseDocumentRequest;

class CourseDocumentController extends AdminController {

    public function __construct()
    {
    }

    public function store(Course $course, CourseDocumentRequest $request)
    {
        $courseDocument = new CourseDocument($request->except('document'));
        $courseDocument->course_id = $course->id;

        $file = $request->file('document');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $savepath = sha1($filename.time()).'.'.$extension;
        $destinationPath = public_path() . '/images/courses/'.$course->id.'/documents/';
        $file->move($destinationPath, $savepath);

        $courseDocument->filename = $filename;
        $courseDocument->file = $savepath;

        $courseDocument->save();

        session()->flash('courseMessage', 'New document has been uploaded.');

        return response()->json([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }

    public function delete(Course $course, CourseDocument $courseDocument)
    {
        $courseDocument->delete();

        session()->flash('courseMessage', 'Course document has been deleted.');
        return redirect()->action('Admin\CourseController@edit', [
            'course' => $course
        ]);
    }
}
