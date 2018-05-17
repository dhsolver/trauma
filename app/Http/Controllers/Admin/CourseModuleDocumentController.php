<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseModule;
use App\CourseModuleDocument;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseModuleDocumentRequest;

class CourseModuleDocumentController extends AdminController {

    public function __construct()
    {
    }

    public function store(Course $course, CourseModule $courseModule, CourseModuleDocumentRequest $request)
    {
        if (empty($request->id)) {
            if ($request->type == 'file' && !$request->hasFile('documents')) {
                return response()->json([
                    'document' => 'Please upload a document.'
                ], 422);
            }

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $courseModuleDocument = new CourseModuleDocument($request->except('documents'));
                    $courseModuleDocument->course_module_id = $courseModule->id;
                    $courseModuleDocument->embedded = $request->has('embedded');

                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $savepath = sha1($filename.time()).'.'.$extension;
                    $destinationPath = public_path() . '/images/courses/'.$course->id.'/modules/'.$courseModule->id.'/';
                    $file->move($destinationPath, $savepath);

                    $courseModuleDocument->filename = $filename;
                    $courseModuleDocument->file = $savepath;

                    $courseModuleDocument->save();
                }
            } else {
                $courseModuleDocument = new CourseModuleDocument($request->all());
                $courseModuleDocument->course_module_id = $courseModule->id;
                $courseModuleDocument->save();
            }

            session()->flash('courseModuleMessage',
                $courseModuleDocument->type === 'url' ?
                    'New URL has been added!' : 'New document has been uploaded.'
            );
        } else {
            $courseModuleDocument = CourseModuleDocument::find($request->id);
            $courseModuleDocument->update($request->except('documents'));
            $courseModuleDocument->embedded = $request->has('embedded');

            if ($request->hasFile('documents'))
            {
                $file = $request->file('documents');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $savepath = sha1($filename.time()).'.'.$extension;
                $destinationPath = public_path() . '/images/courses/'.$course->id.'/modules/'.$courseModule->id.'/';
                $file->move($destinationPath, $savepath);

                $courseModuleDocument->filename = $filename;
                $courseModuleDocument->file = $savepath;
            }

            $courseModuleDocument->save();

            session()->flash('courseModuleMessage',
                $courseModuleDocument->type === 'url' ?
                    'URL has been updated!' : 'Document has been updated.'
            );
        }

        return response()->json([
            'success' => true,
            'redirect' => action('Admin\CourseModuleController@edit', [
                'course' => $course,
                'courseModule' => $courseModule
            ])
        ]);
    }

    public function delete(Course $course, CourseModule $courseModule, CourseModuleDocument $courseModuleDocument)
    {
        $message = $courseModuleDocument->type === 'url' ?
            'URL has been deleted!' :
            'Document has been deleted!';
        $courseModuleDocument->delete();

        session()->flash('courseModuleMessage', $message);
        return redirect()->action('Admin\CourseModuleController@edit', [
            'course' => $course,
            'courseModule' => $courseModule
        ]);
    }
}
