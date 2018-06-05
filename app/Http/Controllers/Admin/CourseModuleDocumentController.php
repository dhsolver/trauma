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
            if ($request->type === 'file') {
                for ($i = 0; $i < count($request->fileKeys); $i++) {
                    $courseModuleDocument = new CourseModuleDocument([
                        'course_module_id' => $courseModule->id,
                        'type' => 'file',
                        'filename' => $request->fileNames[$i],
                        'file' => $request->fileKeys[$i],
                        'embedded' => $request->embedded,
                    ]);
                    $courseModuleDocument->save();
                }
            } else {
                $courseModuleDocument = new CourseModuleDocument([
                    'course_module_id' => $courseModule->id,
                    'type' => 'url',
                    'url' => $request->url,
                ]);
                $courseModuleDocument->save();
            }

            session()->flash('courseModuleMessage',
                $request->type === 'url' ?
                    'New URL has been added!' : 'New document has been uploaded.'
            );
        } else {
            $courseModuleDocument = CourseModuleDocument::find($request->id);
            if ($request->type === 'file') {
                $courseModuleDocument->embedded = $request->embedded;
                if (!empty($request->fileKeys)) {
                    $courseModuleDocument->file = $request->fileKeys[0];
                    $courseModuleDocument->filename = $request->fileNames[0];
                }
            } else {
                $courseModuleDocument->url = $request->url;
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
