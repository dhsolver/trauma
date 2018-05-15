<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseDocument;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseDocumentRequest;

class CourseDocumentController extends AdminController {
    public function store(Course $course, CourseDocumentRequest $request)
    {
        foreach ($request->file('documents') as $document) {
            $courseDocument = new CourseDocument();
            $courseDocument->course_id = $course->id;

            $file = $document;
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $savepath = sha1($filename.time()).'.'.$extension;
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/documents/';
            $file->move($destinationPath, $savepath);

            $courseDocument->filename = $filename;
            $courseDocument->file = $savepath;

            $courseDocument->save();
        }

        session()->flash('courseMessage', 'New administrative document has been uploaded.');
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

        session()->flash('courseMessage', 'An administrative document has been deleted.');
        return redirect()->action('Admin\CourseController@edit', [
            'course' => $course
        ]);
    }
}
