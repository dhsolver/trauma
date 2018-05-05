<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseKey;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseKeyGenerateRequest;

class CourseKeyController extends AdminController {

    private function generateRandomString($length = 6) {
        return substr(str_shuffle(
                str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))
            ), 1, $length
        );
    }

    public function create(Course $course, CourseKeyGenerateRequest $request)
    {
        for ($i = 0; $i < $request->count; $i++) {
            $key = new CourseKey;
            $key->course_id = $course->id;
            $key->key = $request->prefix.$this->generateRandomString();
            $key->tag = $request->tag;

            $key->save();
        }

        session()->flash('courseMessage', "$request->count key(s) have bene generated.");

        return response()->json([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }
}
