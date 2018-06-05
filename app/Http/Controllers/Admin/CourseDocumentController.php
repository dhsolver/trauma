<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseDocument;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseDocumentRequest;

class CourseDocumentController extends AdminController {
    public function store(Course $course, CourseDocumentRequest $request)
    {
        for ($i = 0; $i < count($request->fileKeys); $i++) {
            CourseDocument::create([
                'course_id' => $course->id,
                'filename' => $request->fileNames[$i],
                'file' => $request->fileKeys[$i],
            ]);
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
