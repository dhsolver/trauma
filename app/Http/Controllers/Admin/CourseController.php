<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\User;
use App\UsersCoursesRegistration;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class CourseController extends AdminController {
    public function index(Request $request) {
        if (empty($request->id)) {
            $courses = Course::where('id', '>', 0);
        } else {
            $courses = Course::where('id', $request->id);
        }
        if (!empty($request->title)) $courses = $courses->where('title', 'like', "%$request->title%");
        if (!empty($request->location)) $courses = $courses->where('location', 'like', "%$request->location%");

        $courses = $courses->orderBy('title', 'asc')
            ->get();

        $users = User::all()->keyBy('id')->toArray();
        return view('admin.courses.index', compact('courses', 'users'));
    }

    public function create() {
        return view('admin.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $course = new Course($request->except('photo', 'online_only'));

        if ($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $photo = sha1($filename . time()) . '.' . $extension;
            $course->photo = $photo;
        }
        if ($request->online_only == '1') {
            $course->online_only = true;
        } else {
            $course->online_only = false;
        }
        $course->save();

        if ($request->hasFile('photo'))
        {
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/';
            $request->file('photo')->move($destinationPath, $photo);
        }

        session()->flash('courseMessage', 'Course has been created!');
        return redirect()->action('Admin\CourseController@index');
    }

    public function edit(Course $course)
    {
        $faculties = User::where('role', 'faculty')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get()
            ->keyBy('id')
            ->toArray();

        return view('admin.courses.edit', compact('course', 'faculties'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $photo = '';
        if ($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file -> getClientOriginalExtension();
            $photo = sha1($filename . time()) . '.' . $extension;
            $destinationPath = public_path() . '/images/courses/'.$course->id.'/';
            $request->file('photo')->move($destinationPath, $photo);
            $course->photo = $photo;
        }

        $course->update($request->except('photo', 'online_only'));
        if ($request->online_only == '1') {
            $course->online_only = true;
        } else {
            $course->online_only = false;
            if ($course->date_start > $course->date_end) {
                return redirect()->action('Admin\CourseController@edit', $course)
                            ->withMessage('Please enter correct dates')
                            ->withInput();
            }
        }

        if (empty($request->instructors)) $course->instructors = [];

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

    public function disable(Course $course)
    {
        $course->enabled = false;
        $course->published = false;
        $course->save();
        session()->flash('courseMessage', 'Course has been disabled!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    public function enable(Course $course)
    {
        $course->enabled = true;
        $course->save();
        session()->flash('courseMessage', 'Course has been enabled!');
        return redirect()->action('Admin\CourseController@edit', $course);
    }

    private function copyPublicFile($path, $newPath)
    {
        if (empty($newPath)) return;
        $fullPath = public_path($newPath);
        if (!File::exists(dirname($fullPath))) {
            File::makeDirectory(dirname($fullPath), 0755, true, true);
        }
        File::copy(public_path($path), $fullPath);
    }

    /*
    // $this->copyChildren($course, $newCourse, 'modules');
    private function copyChildren($object, $newObject, $relation)
    {
        $object->load($relation);
        foreach ($course->getRelations() as $relation => $items){
            foreach ($items as $item){
                unset($item->id);
                $newObject->{$relation}()->create($item->toArray());
            }

        }
    }*/

    public function copy(Course $course)
    {
        $newCourse = $course->replicate();
        $newCourse->title = 'Copy - '.$newCourse->title;
        $newCourse->continuing_education = '';
        $newCourse->published = false;
        $newCourse->enabled = true;

        $newCourse->push();
        $this->copyPublicFile($course->photo_path, $newCourse->photo_path);

        foreach ($course->modules as $module => $courseModule) {
            $newCourseModule = $courseModule->replicate();
            $newCourseModule->course_id = $newCourse->id;
            $newCourseModule->push();

            foreach ($courseModule->documents as $document => $courseModuleDocument) {
                $newCourseModuleDocument = $courseModuleDocument->replicate();
                $newCourseModuleDocument->course_module_id = $newCourseModule->id;
                $newCourseModuleDocument->push();

                if ($newCourseModuleDocument->type == 'file') {
                    $this->copyPublicFile($courseModuleDocument->file_path, $newCourseModuleDocument->file_path);
                }
            }
        }

        session()->flash('courseMessage', 'Course has been copied!');
        return redirect()->action('Admin\CourseController@edit', $newCourse);
    }


    public function exportStudents(Course $course)
    {
        $this->download_send_headers('course_students.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array(
            'Student Id',
            'Full Name',
            'Email',
            // 'Channel',
            'Registered',
            'Completed',
            'Certified',
        ));
        foreach ($course->registrations as $registration) {
            fputcsv($output, array(
                $registration->user->id,
                $registration->user->first_name.' '.$registration->user->last_name,
                $registration->user->email,
                // $registration->method == 'key' ? 'Course Key' : 'Paypal',
                $registration->registered_at,
                $registration->completed_at,
                $registration->certified_at,
            ));
        }

        fclose($output);
        echo ob_get_clean();
        die;
    }


    public function certifyStudent(Course $course, UsersCoursesRegistration $registration)
    {
        if ($registration->completed_at && empty($registration->certified_at)) {
            $registration->certified_at = Carbon::now()->toDateTimeString();
            $registration->save();

            session()->flash('courseMessage', 'You just certified a student for completing this course.');
        }

        return redirect()->action('Admin\CourseController@edit', $course);
    }


    public function uncertifyStudent(Course $course, UsersCoursesRegistration $registration)
    {
        if ($registration->completed_at && $registration->certified_at) {
            $registration->certified_at = null;
            $registration->save();

            session()->flash('courseMessage', 'You just uncertified a student for completing this course.');
        }

        return redirect()->action('Admin\CourseController@edit', $course);
    }
}
